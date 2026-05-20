<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Variant::with(['product.images' => fn($q) => $q->where('is_primary', 1)])
            ->withCount('orderItems');

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) => $q->where('product_name', 'like', '%' . $request->search . '%'))
                  ->orWhere('sku_code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('stock_filter')) {
            match($request->stock_filter) {
                'low'      => $query->where('stock_qty', '<=', 5)->where('stock_qty', '>', 0),
                'out'      => $query->where('stock_qty', 0),
                'in_stock' => $query->where('stock_qty', '>', 5),
                default    => null,
            };
        }

        $variants = $query->orderBy('stock_qty')->paginate(20)->withQueryString();

        // Recent stock log
        $recentLogs = StockLog::with(['variant.product', 'user'])
            ->latest('log_date')
            ->take(10)
            ->get();

        return view('admin.inventory.index', compact('variants', 'recentLogs'));
    }

    public function updateStock(Request $request, Variant $variant)
    {
        $validated = $request->validate([
            'quantity'    => ['required', 'integer'],
            'change_type' => ['required', 'in:Restock,Adjustment,Correction'],
            'note'        => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $variant) {
            $oldQty = $variant->stock_qty;
            $newQty = $oldQty + $validated['quantity'];

            if ($newQty < 0) {
                throw new \Exception('Stock quantity cannot go below zero.');
            }

            $variant->update(['stock_qty' => $newQty]);

            StockLog::create([
                'variant_id'       => $variant->variant_id,
                'user_id'          => session('admin_id'),
                'change_type'      => $validated['change_type'],
                'quantity_changed' => $validated['quantity'],
                'log_date'         => now(),
            ]);
        });

        return back()->with('success', "Stock updated for {$variant->product->product_name} ({$variant->size}/{$variant->colour}).");
    }
}
