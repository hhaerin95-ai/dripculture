<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Summary stats ──────────────────────────────────────────────
        $totalOrders   = Order::count();
        $totalRevenue  = Order::where('order_status', '!=', 'Cancelled')->sum('total_amount');
        $totalProducts = Product::where('status', 'Active')->count();
        $totalUsers    = User::where('role_id', 2)->count(); // role 2 = Customer

        // ── Low-stock alerts (stock <= 5) ──────────────────────────────
        $lowStockVariants = Variant::with('product')
            ->where('stock_qty', '<=', 5)
            ->orderBy('stock_qty')
            ->get();

        // ── Recent orders (latest 8) ───────────────────────────────────
        $recentOrders = Order::with(['user', 'payment'])
            ->latest('order_date')
            ->take(8)
            ->get();

        // ── Orders by status (for donut chart) ────────────────────────
        $ordersByStatus = Order::select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        // ── Revenue last 7 days (for line chart) ──────────────────────
        $revenueChart = Order::select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('order_date', '>=', now()->subDays(6))
            ->where('order_status', '!=', 'Cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ── Pending payments ───────────────────────────────────────────
        $pendingPayments = Payment::where('payment_status', 'Pending')->count();

        return view('admin.dashboard.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalUsers',
            'lowStockVariants',
            'recentOrders',
            'ordersByStatus',
            'revenueChart',
            'pendingPayments'
        ));
    }
}
