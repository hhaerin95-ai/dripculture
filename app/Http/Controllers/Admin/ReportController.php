<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   now()->format('Y-m-d'));
        $status   = $request->get('status', '');

        $query = Order::with(['user', 'items.variant.product', 'payment'])
            ->whereBetween(DB::raw('DATE(order_date)'), [$dateFrom, $dateTo]);

        if ($status) {
            $query->where('order_status', $status);
        }

        $orders = $query->latest('order_date')->get();

        // Summary stats for the filtered period
        $totalRevenue   = $orders->where('order_status', '!=', 'Cancelled')->sum('total_amount');
        $totalOrders    = $orders->count();
        $cancelledCount = $orders->where('order_status', 'Cancelled')->count();
        $deliveredCount = $orders->where('order_status', 'Delivered')->count();

        // Top selling products
        $topProducts = OrderItem::select('variant_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->with('variant.product')
            ->whereHas('order', fn($q) => $q->whereBetween(DB::raw('DATE(order_date)'), [$dateFrom, $dateTo])
                                             ->where('order_status', '!=', 'Cancelled'))
            ->groupBy('variant_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'cancelledCount',
            'deliveredCount',
            'topProducts',
            'dateFrom',
            'dateTo',
            'status'
        ));
    }

    /** Export filtered orders to CSV. */
    public function exportCsv(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   now()->format('Y-m-d'));
        $status   = $request->get('status', '');

        $query = Order::with(['user', 'payment'])
            ->whereBetween(DB::raw('DATE(order_date)'), [$dateFrom, $dateTo]);

        if ($status) {
            $query->where('order_status', $status);
        }

        $orders = $query->latest('order_date')->get();

        $filename = "sales_report_{$dateFrom}_to_{$dateTo}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');

            // CSV header row
            fputcsv($handle, [
                'Order ID', 'Customer', 'Email', 'Order Date',
                'Total Amount (RM)', 'Order Status', 'Payment Method', 'Payment Status',
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_id,
                    $order->user->full_name ?? 'N/A',
                    $order->user->email     ?? 'N/A',
                    $order->order_date,
                    number_format($order->total_amount, 2),
                    $order->order_status,
                    $order->payment->payment_method  ?? 'N/A',
                    $order->payment->payment_status  ?? 'N/A',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /** Export to PDF (simple HTML-based PDF via browser print). */
    public function exportPdf(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   now()->format('Y-m-d'));
        $status   = $request->get('status', '');

        $query = Order::with(['user', 'payment'])
            ->whereBetween(DB::raw('DATE(order_date)'), [$dateFrom, $dateTo]);

        if ($status) {
            $query->where('order_status', $status);
        }

        $orders       = $query->latest('order_date')->get();
        $totalRevenue = $orders->where('order_status', '!=', 'Cancelled')->sum('total_amount');

        return view('admin.reports.pdf', compact('orders', 'totalRevenue', 'dateFrom', 'dateTo', 'status'));
    }
}
