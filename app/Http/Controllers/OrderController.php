<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withCount('items')
            ->where('user_id', Auth::id())
            ->latest('order_date')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load(['items.variant.product', 'payment', 'address']);
        return view('orders.show', compact('order'));
    }
}
