<?php

use App\Http\Controllers\DailyCostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Membership\MembershipController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Products\BrandController;
use App\Http\Controllers\Products\CategoryController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\UnitController;
use App\Http\Controllers\Purchases\AddPurchaseController;
use App\Http\Controllers\Purchases\PurchaseListController;
use App\Http\Controllers\Purchases\PurchaseOrderController;
use App\Http\Controllers\Purchases\PurchaseReturnController;
use App\Http\Controllers\Purchases\RequisitionController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Sales\AddSaleController;
use App\Http\Controllers\Sales\AllSalesController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Settings\BarcodeSettingController;
use App\Http\Controllers\Settings\BusinessSettingController;
use App\Http\Controllers\Settings\InvoiceSettingController;
use App\Http\Controllers\Settings\TaxRateController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Login, Logout, Password Reset, and Profile routes are provided by
// Laravel Breeze via routes/auth.php (required at the bottom of this file).
// Breeze's public Register route has been removed — see routes/auth.php —
// since only an Admin may create new accounts, via Staff Accounts below.

// --- Everyone signed in (Admin + Salesman) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Products: viewing the list is fine for both roles; managing it is admin-only (below).
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // POS terminal — the core "ring up a sale" flow, open to both roles.
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/scan', [PosController::class, 'scan'])->name('scan');
        Route::post('/qty/{productId}', [PosController::class, 'updateQty'])->name('qty');
        Route::delete('/item/{productId}', [PosController::class, 'remove'])->name('remove');
        Route::post('/discount-tax', [PosController::class, 'setDiscountAndTax'])->name('discountTax');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
        Route::get('/receipt', [PosController::class, 'receipt'])->name('receipt');
    });

    Route::get('/sales', [AllSalesController::class, 'index'])->name('sales.all');
    Route::get('/sales/add', [AddSaleController::class, 'index'])->name('sales.add');
    Route::post('/sales/add', [AddSaleController::class, 'store'])->name('sales.add.store');
    Route::get('/sales/orders', [SalesOrderController::class, 'index'])->name('sales.orders.index');
    Route::post('/sales/orders', [SalesOrderController::class, 'store'])->name('sales.orders.store');
    Route::patch('/sales/orders/{salesOrder}', [SalesOrderController::class, 'updateStatus'])->name('sales.orders.status');

    // Membership: front-desk enroll/list is open to both roles.
    Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
    Route::get('/membership/add', [MembershipController::class, 'create'])->name('membership.create');
    Route::post('/membership', [MembershipController::class, 'store'])->name('membership.store');
    Route::post('/membership/{membership}/renew', [MembershipController::class, 'renew'])->name('membership.renew');
    Route::post('/membership/{membership}/revoke', [MembershipController::class, 'revoke'])->name('membership.revoke');
});

// --- Admin only ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Products: register new product, edit, archive, price updates, and the
    // Categories / Units / Brands reference lists are all admin-only.
    Route::get('/products/add', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/{product}/archive', [ProductController::class, 'archive'])->name('products.archive');
    Route::post('/products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/products/price', [ProductController::class, 'priceSearch'])->name('products.price');
    Route::patch('/products/{product}/price', [ProductController::class, 'updatePrice'])->name('products.price.update');

    Route::get('/products/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/products/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/products/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/products/units', [UnitController::class, 'index'])->name('units.index');
    Route::post('/products/units', [UnitController::class, 'store'])->name('units.store');
    Route::delete('/products/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

    Route::get('/products/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/products/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::delete('/products/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Purchases: the whole module — requisition, orders, receiving stock,
    // purchase history, and returns — is invisible and inaccessible to Salesman.
    Route::get('/purchases/requisition', [RequisitionController::class, 'index'])->name('purchases.requisition.index');
    Route::post('/purchases/requisition', [RequisitionController::class, 'store'])->name('purchases.requisition.store');
    Route::patch('/purchases/requisition/{requisition}', [RequisitionController::class, 'updateStatus'])->name('purchases.requisition.status');

    Route::get('/purchases/orders', [PurchaseOrderController::class, 'index'])->name('purchases.orders.index');
    Route::post('/purchases/orders', [PurchaseOrderController::class, 'store'])->name('purchases.orders.store');
    Route::patch('/purchases/orders/{order}', [PurchaseOrderController::class, 'updateStatus'])->name('purchases.orders.status');

    Route::get('/purchases/add', [AddPurchaseController::class, 'index'])->name('purchases.add.index');
    Route::post('/purchases/add', [AddPurchaseController::class, 'store'])->name('purchases.add.store');

    Route::get('/purchases/list', [PurchaseListController::class, 'index'])->name('purchases.list');

    Route::get('/purchases/returns', [PurchaseReturnController::class, 'index'])->name('purchases.returns.index');
    Route::post('/purchases/returns', [PurchaseReturnController::class, 'store'])->name('purchases.returns.store');

    // Refunds — reversing a sale is admin-only.
    Route::get('/refund', [RefundController::class, 'index'])->name('refund.index');
    Route::post('/refund/{sale}', [RefundController::class, 'process'])->name('refund.process');

    // Reports — revenue, net profit, ledger export.
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    // Daily cost + survey feed the Net Profit numbers in Reports.
    Route::get('/daily-cost', [DailyCostController::class, 'index'])->name('daily-cost.index');
    Route::post('/daily-cost', [DailyCostController::class, 'store'])->name('daily-cost.store');
    Route::delete('/daily-cost/{dailyCost}', [DailyCostController::class, 'destroy'])->name('daily-cost.destroy');

    Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
    Route::post('/survey/sync', [SurveyController::class, 'sync'])->name('survey.sync');
    Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');
    Route::delete('/survey/{surveyRecord}', [SurveyController::class, 'destroy'])->name('survey.destroy');

    // Membership discount settings — money-related config, admin-only.
    Route::get('/membership/settings', [MembershipController::class, 'editSettings'])->name('membership.settings');
    Route::put('/membership/settings', [MembershipController::class, 'updateSettings'])->name('membership.settings.update');

    // Business / invoice / barcode / tax settings.
    Route::get('/settings/business', [BusinessSettingController::class, 'edit'])->name('settings.business');
    Route::put('/settings/business', [BusinessSettingController::class, 'update'])->name('settings.business.update');
    Route::get('/settings/invoice', [InvoiceSettingController::class, 'edit'])->name('settings.invoice');
    Route::put('/settings/invoice', [InvoiceSettingController::class, 'update'])->name('settings.invoice.update');
    Route::get('/settings/barcode', [BarcodeSettingController::class, 'edit'])->name('settings.barcode');
    Route::put('/settings/barcode', [BarcodeSettingController::class, 'update'])->name('settings.barcode.update');
    Route::get('/settings/tax', [TaxRateController::class, 'index'])->name('settings.tax');
    Route::post('/settings/tax', [TaxRateController::class, 'store'])->name('settings.tax.store');
    Route::delete('/settings/tax/{taxRate}', [TaxRateController::class, 'destroy'])->name('settings.tax.destroy');

    // Staff accounts — where Admin logins are created for Salesman users.
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
