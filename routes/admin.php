<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PaymentController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All routes here are prefixed with /admin
| Guest routes: login only
| Protected routes: everything else (requires AdminAuthenticated middleware)
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // ── Guest-only routes ──────────────────────────────────────────────
    Route::middleware('guest')->group(function () {
        Route::get('login',  [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // ── Authenticated admin routes ─────────────────────────────────────
    Route::middleware('admin.auth')->group(function () {

        // Logout
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Products ─ full CRUD
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/',                [ProductController::class, 'index'])->name('index');
            Route::get('create',           [ProductController::class, 'create'])->name('create');
            Route::post('/',               [ProductController::class, 'store'])->name('store');
            Route::get('{product}',        [ProductController::class, 'show'])->name('show');
            Route::get('{product}/edit',   [ProductController::class, 'edit'])->name('edit');
            Route::put('{product}',        [ProductController::class, 'update'])->name('update');
            Route::delete('{product}',     [ProductController::class, 'destroy'])->name('destroy');
            Route::patch('{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');

            // Variants
            Route::post('{product}/variants',          [ProductController::class, 'storeVariant'])->name('variants.store');
            Route::put('{product}/variants/{variant}', [ProductController::class, 'updateVariant'])->name('variants.update');
            Route::delete('{product}/variants/{variant}', [ProductController::class, 'destroyVariant'])->name('variants.destroy');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/',                [OrderController::class, 'index'])->name('index');
            Route::get('{order}',          [OrderController::class, 'show'])->name('show');
            Route::patch('{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        });

        // Payments
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/',                   [PaymentController::class, 'index'])->name('index');
            Route::patch('{payment}/verify',  [PaymentController::class, 'verify'])->name('verify');
            Route::patch('{payment}/reject',  [PaymentController::class, 'reject'])->name('reject');
        });

        // Inventory
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/',                        [InventoryController::class, 'index'])->name('index');
            Route::patch('variants/{variant}/stock', [InventoryController::class, 'updateStock'])->name('update-stock');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',            [ReportController::class, 'index'])->name('index');
            Route::get('export/csv',   [ReportController::class, 'exportCsv'])->name('export.csv');
            Route::get('export/pdf',   [ReportController::class, 'exportPdf'])->name('export.pdf');
        });
    });
});
