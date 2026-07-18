<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List Products — visible to both Admin and Salesman, but the Blade
     * view hides the Add/Edit/Archive/Update-Price actions from Salesman.
     */
    public function index(Request $request)
    {
        $search = $request->query('q', '');
        $showArchived = $request->boolean('archived');

        $items = Product::query()
            ->when($search, fn ($q) => $q->where(fn ($q2) => $q2
                ->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")))
            ->when(! $showArchived, fn ($q) => $q->where('status', '!=', 'archived'))
            ->orderByDesc('created_at')
            ->get();

        return view('products.index', [
            'items' => $items,
            'search' => $search,
            'showArchived' => $showArchived,
        ]);
    }

    /**
     * Printable barcode/QR label for one product — open to both roles
     * (same visibility as List Products), since restocking staff may need
     * to print a replacement shelf tag too.
     */
    public function qr(Product $product)
    {
        return view('products.qr', ['product' => $product]);
    }

    // --- Everything below is admin-only (enforced by route middleware) ---

    public function create()
    {
        return view('products.create', [
            'categories' => Category::orderBy('name')->pluck('name'),
            'brands' => Brand::orderBy('name')->get(),
            'units' => Unit::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barcode' => ['required', 'string', 'unique:products,barcode'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'brand' => ['nullable', 'string'],
            'unit' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
        ]);

        $data['unit'] = $data['unit'] ?: 'Piece';
        $data['status'] = $data['quantity'] > 0 ? 'available' : 'sold';

        Product::create($data);

        return redirect()->route('products.index')->with('success', "Successfully stocked {$data['quantity']} item(s)!");
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->pluck('name'),
            'brands' => Brand::orderBy('name')->get(),
            'units' => Unit::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'brand' => ['nullable', 'string'],
            'unit' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
        ]);

        $data['unit'] = $data['unit'] ?: 'Piece';
        $data['status'] = $data['quantity'] > 0 ? 'available' : 'sold';

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function archive(Product $product)
    {
        $product->update(['status' => 'archived', 'quantity' => 0]);

        return back()->with('success', "Archived \"{$product->name}\".");
    }

    public function restore(Product $product)
    {
        $product->update(['status' => 'available']);

        return back()->with('success', "Restored \"{$product->name}\".");
    }

    public function priceSearch(Request $request)
    {
        $search = $request->query('q', '');

        $items = $search === '' ? collect() : Product::query()
            ->where('status', '!=', 'archived')
            ->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%"))
            ->orderBy('name')
            ->limit(50)
            ->get();

        return view('products.price', ['items' => $items, 'search' => $search]);
    }

    public function updatePrice(Request $request, Product $product)
    {
        $data = $request->validate(['price' => ['required', 'numeric', 'min:0']]);
        $product->update($data);

        return back()->with('success', 'Price updated.');
    }
}
