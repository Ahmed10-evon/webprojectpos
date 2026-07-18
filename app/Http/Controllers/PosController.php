<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use App\Models\Product;
use App\Models\Sale;
use App\Models\TaxRate;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * The POS terminal. Available to both Admin and Salesman — this is the
 * bread-and-butter "ring up a sale" screen neither role should be blocked
 * from. The cart itself lives in the session (server-side, cookie only
 * carries the session ID), so it survives a page reload but is private
 * to whoever is signed in on that browser.
 */
class PosController extends Controller
{
    const PAYMENT_METHODS = ['cash', 'bkash', 'nagad', 'upay', 'rocket', 'bank/card'];
    const CART_SESSION_KEY = 'pos.cart';

    public function __construct(protected CurrencyService $currency)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->cart($request);
        $taxRates = TaxRate::orderBy('name')->get();

        // BDT-per-USD rate for the BDT/USD toggle in the checkout panel —
        // null if the currency API isn't configured or is down, in which
        // case the view just doesn't show the toggle at all.
        $currencyRates = $this->currency->rates();
        $usdRate = $currencyRates['rates']['BDT'] ?? null;

        return view('pos.index', [
            'cart' => $cart,
            'taxRates' => $taxRates,
            'paymentMethods' => self::PAYMENT_METHODS,
            'totals' => $this->totals($cart, (float) $request->session()->get('pos.discount', 0), $request->session()->get('pos.tax_rate_id')),
            'usdRate' => $usdRate,
        ]);
    }

    public function scan(Request $request)
    {
        $request->validate(['barcode' => ['required', 'string']]);

        $product = Product::where('barcode', $request->input('barcode'))->first();

        if (! $product) {
            return back()->with('error', 'Product not found! Check the barcode.');
        }

        $cart = $this->cart($request);
        $currentQty = $cart[$product->id]['qty'] ?? 0;

        if ($currentQty + 1 > $product->quantity) {
            return back()->with('error', 'Not enough stock available for this item!');
        }

        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'barcode' => $product->barcode,
            'price' => (float) $product->price,
            'stock' => $product->quantity,
            'qty' => $currentQty + 1,
        ];

        $request->session()->put(self::CART_SESSION_KEY, $cart);

        return back();
    }

    public function updateQty(Request $request, int $productId)
    {
        $increment = $request->boolean('increment');
        $cart = $this->cart($request);

        if (! isset($cart[$productId])) {
            return back();
        }

        $newQty = $cart[$productId]['qty'] + ($increment ? 1 : -1);

        if ($newQty <= 0) {
            return back();
        }

        if ($newQty > $cart[$productId]['stock']) {
            return back()->with('error', "Cannot exceed available physical stock ({$cart[$productId]['stock']} available).");
        }

        $cart[$productId]['qty'] = $newQty;
        $request->session()->put(self::CART_SESSION_KEY, $cart);

        return back();
    }

    public function remove(Request $request, int $productId)
    {
        $cart = $this->cart($request);
        unset($cart[$productId]);
        $request->session()->put(self::CART_SESSION_KEY, $cart);

        return back();
    }

    public function setDiscountAndTax(Request $request)
    {
        $data = $request->validate([
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax_rate_id' => ['nullable', 'exists:tax_rates,id'],
        ]);

        $request->session()->put('pos.discount', $data['discount'] ?? 0);
        $request->session()->put('pos.tax_rate_id', $data['tax_rate_id'] ?? null);

        return back();
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:'.implode(',', self::PAYMENT_METHODS)],
            'trx_id' => ['nullable', 'string'],
        ]);

        $cart = $this->cart($request);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        $discount = (float) $request->session()->get('pos.discount', 0);
        $taxRateId = $request->session()->get('pos.tax_rate_id');
        $taxRate = $taxRateId ? TaxRate::find($taxRateId) : null;

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $discount = min(max($discount, 0), $subtotal);
        $subtotalAfterDiscount = $subtotal - $discount;
        $taxTotal = $taxRate ? round($subtotalAfterDiscount * ($taxRate->rate_percent / 100)) : 0;

        $dbPaymentMethod = $data['payment_method'] === 'bank/card' ? 'cash' : $data['payment_method'];
        $dbTrxId = match (true) {
            $data['payment_method'] === 'bank/card' => 'BANK/CARD-SALE',
            $data['payment_method'] === 'cash' => 'DIRECT-SALE',
            default => $data['trx_id'] ?? null,
        };

        DB::transaction(function () use ($cart, $subtotal, $discount, $taxTotal, $dbPaymentMethod, $dbTrxId, $request) {
            $remainingDiscount = $discount;
            $remainingTax = $taxTotal;

            // Flatten to one row per physical unit sold, matching the
            // original app's sales-table shape, spreading discount/tax
            // proportionally so every downstream report just sums amount_paid.
            $units = [];
            foreach ($cart as $item) {
                for ($i = 0; $i < $item['qty']; $i++) {
                    $units[] = $item;
                }
            }

            foreach ($units as $idx => $item) {
                $isLast = $idx === count($units) - 1;
                $rowDiscount = $isLast ? $remainingDiscount : ($subtotal > 0 ? round(($item['price'] / $subtotal) * $discount) : 0);
                if (! $isLast) {
                    $remainingDiscount -= $rowDiscount;
                }
                $rowTax = $isLast ? $remainingTax : ($subtotal > 0 ? round(($item['price'] / $subtotal) * $taxTotal) : 0);
                if (! $isLast) {
                    $remainingTax -= $rowTax;
                }

                Sale::create([
                    'product_id' => $item['id'],
                    'user_id' => $request->user()->id,
                    'payment_method' => $dbPaymentMethod,
                    'transaction_id' => $dbTrxId,
                    'amount_paid' => $item['price'] - $rowDiscount + $rowTax,
                    'discount_amount' => $rowDiscount,
                    'tax_amount' => $rowTax,
                    'status' => 'completed',
                    'sold_at' => now(),
                ]);
            }

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (! $product) {
                    continue;
                }
                $newQuantity = $product->quantity - $item['qty'];
                $product->update([
                    'quantity' => max($newQuantity, 0),
                    'status' => $newQuantity <= 0 ? 'sold' : 'available',
                ]);
            }
        });

        $lastReceipt = [
            'items' => $cart,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $taxTotal,
            'total' => $subtotalAfterDiscount + $taxTotal,
            'payment_method' => $data['payment_method'],
            'trx_id' => $dbTrxId,
            'business' => BusinessSetting::first(),
            'sold_at' => now(),
            'receipt_ref' => 'CRV-'.now()->format('YmdHis'),
        ];

        $request->session()->forget([self::CART_SESSION_KEY, 'pos.discount', 'pos.tax_rate_id']);
        $request->session()->put('pos.last_receipt', $lastReceipt);

        return redirect()->route('pos.receipt')->with('success', 'Sale recorded!');
    }

    public function receipt(Request $request)
    {
        $receipt = $request->session()->get('pos.last_receipt');

        if (! $receipt) {
            return redirect()->route('pos.index');
        }

        return view('pos.receipt', ['receipt' => $receipt]);
    }

    private function cart(Request $request): array
    {
        return $request->session()->get(self::CART_SESSION_KEY, []);
    }

    private function totals(array $cart, float $discount, ?int $taxRateId): array
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $discount = min(max($discount, 0), $subtotal);
        $afterDiscount = $subtotal - $discount;
        $taxRate = $taxRateId ? TaxRate::find($taxRateId) : null;
        $tax = $taxRate ? round($afterDiscount * ($taxRate->rate_percent / 100)) : 0;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'tax_rate' => $taxRate,
            'total' => $afterDiscount + $tax,
        ];
    }
}
