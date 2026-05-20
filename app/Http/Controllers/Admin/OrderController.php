<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** List all orders with search/filter. */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment'])
            ->withCount('items');

        if ($request->filled('search')) {
            $query->where('order_id', $request->search)
                  ->orWhereHas('user', fn($q) => $q->where('full_name', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->latest('order_date')->paginate(15)->withQueryString();

        $statusCounts = Order::select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /** Show single order detail. */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'address',
            'items.variant.product.images',
            'payment',
            'histories.updatedBy',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /** Update order status and record history. */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => ['required', 'in:Pending,Processing,Packed,Shipped,Delivered,Cancelled'],
            'note'         => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $order) {
            $oldStatus = $order->order_status;

            $order->update(['order_status' => $validated['order_status']]);

            // Insert into order_history
            OrderHistory::create([
                'order_id'   => $order->order_id,
                'user_id'    => session('admin_id'),
                'status'     => $validated['order_status'],
                'note'       => $validated['note'] ?? "Status changed from {$oldStatus} to {$validated['order_status']}",
                'updated_at' => now(),
            ]);
        });

        return back()->with('success', 'Order status updated to ' . $validated['order_status'] . '.');
    }
}
