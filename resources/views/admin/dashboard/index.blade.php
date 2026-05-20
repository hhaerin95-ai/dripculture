@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@push('styles')
<style>
    .chart-container { position: relative; height: 220px; }
    .low-stock-dot {
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .dot-critical { background: var(--danger); }
    .dot-low      { background: var(--warning); }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Overview</h1>
        <p class="page-subtitle">{{ now()->format('l, d F Y') }}</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn-outline-dim">
        <i class="bi bi-download"></i> Export Report
    </a>
</div>

{{-- ── STAT CARDS ──────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-change">All time</div>
            <i class="bi bi-bag-check stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value" style="font-size:22px;">RM {{ number_format($totalRevenue, 2) }}</div>
            <div class="stat-change">Excluding cancelled</div>
            <i class="bi bi-currency-dollar stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Active Products</div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-change">In catalogue</div>
            <i class="bi bi-box-seam stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Customers</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-change">Registered accounts</div>
            <i class="bi bi-people stat-icon"></i>
        </div>
    </div>
</div>

{{-- Pending alerts row --}}
@if($pendingPayments > 0 || $lowStockVariants->count() > 0)
<div class="row g-3 mb-4">
    @if($pendingPayments > 0)
    <div class="col-12 col-md-6">
        <div class="alert-dark alert-warning-dark" style="margin-bottom:0;">
            <i class="bi bi-credit-card"></i>
            <div>
                <strong>{{ $pendingPayments }} payment(s) awaiting verification</strong>
                <a href="{{ route('admin.payments.index') }}?status=Pending" style="color:inherit;margin-left:8px;font-size:12px;">View →</a>
            </div>
        </div>
    </div>
    @endif
    @if($lowStockVariants->count() > 0)
    <div class="col-12 col-md-6">
        <div class="alert-dark alert-error-dark" style="margin-bottom:0;">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>{{ $lowStockVariants->count() }} variant(s) low on stock</strong>
                <a href="{{ route('admin.inventory.index') }}?stock_filter=low" style="color:inherit;margin-left:8px;font-size:12px;">Manage →</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

{{-- ── CHARTS + RECENT ORDERS ──────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    {{-- Revenue chart --}}
    <div class="col-12 col-lg-8">
        <div class="card-dark">
            <div class="card-header-dark">Revenue — Last 7 Days</div>
            <div class="card-body-dark">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders by status donut --}}
    <div class="col-12 col-lg-4">
        <div class="card-dark">
            <div class="card-header-dark">Orders by Status</div>
            <div class="card-body-dark">
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── RECENT ORDERS TABLE ─────────────────────────────────────────── --}}
<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card-dark">
            <div class="card-header-dark" style="display:flex;justify-content:space-between;align-items:center;">
                <span>Recent Orders</span>
                <a href="{{ route('admin.orders.index') }}" style="font-size:11px;color:var(--accent);text-decoration:none;">View All →</a>
            </div>
            <div class="card-body-dark" style="padding:0;">
                <table class="table-dark-custom">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="mono" style="color:var(--accent);">
                                <a href="{{ route('admin.orders.show', $order) }}" style="color:inherit;text-decoration:none;">
                                    #{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $order->user->full_name ?? 'N/A' }}</td>
                            <td class="mono">RM {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge-status badge-{{ strtolower($order->order_status) }}">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted);">{{ \Carbon\Carbon::parse($order->order_date)->format('d M y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:32px;">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Low stock panel --}}
    <div class="col-12 col-lg-4">
        <div class="card-dark">
            <div class="card-header-dark" style="display:flex;justify-content:space-between;align-items:center;">
                <span>Low Stock Alert</span>
                <a href="{{ route('admin.inventory.index') }}" style="font-size:11px;color:var(--accent);text-decoration:none;">Manage →</a>
            </div>
            <div class="card-body-dark" style="padding:0;">
                @forelse($lowStockVariants->take(8) as $variant)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-bottom:1px solid var(--border);">
                    <div>
                        <div style="font-size:13px;color:var(--text-primary);">{{ Str::limit($variant->product->product_name ?? 'N/A', 22) }}</div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $variant->size }} / {{ $variant->colour }}</div>
                    </div>
                    <div style="text-align:right;">
                        <span class="mono" style="font-size:16px;font-weight:700;color:{{ $variant->stock_qty == 0 ? 'var(--danger)' : 'var(--warning)' }}">
                            {{ $variant->stock_qty }}
                        </span>
                        <div style="font-size:10px;color:var(--text-muted);">units</div>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding:32px;">
                    <i class="bi bi-check-circle" style="font-size:32px;color:var(--success);"></i>
                    <p>All stock levels are healthy!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = '#666';
Chart.defaults.borderColor = '#2a2a2a';
Chart.defaults.font.family = "'DM Sans', sans-serif";

// Revenue Line Chart
const revenueData = @json($revenueChart);
const revLabels   = revenueData.map(r => r.date);
const revValues   = revenueData.map(r => parseFloat(r.revenue));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revLabels,
        datasets: [{
            label: 'Revenue (RM)',
            data: revValues,
            borderColor: '#e8ff00',
            backgroundColor: 'rgba(232,255,0,.07)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#e8ff00',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#1e1e1e' }, ticks: { callback: v => 'RM ' + v } },
            x: { grid: { display: false } }
        }
    }
});

// Status Donut Chart
const statusData = @json($ordersByStatus);
const statusLabels = Object.keys(statusData);
const statusValues = Object.values(statusData);
const statusColors = {
    'Pending':    '#ffb300',
    'Processing': '#40c4ff',
    'Packed':     '#9966ff',
    'Shipped':    '#00e676',
    'Delivered':  '#00e676',
    'Cancelled':  '#ff4545',
};

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusValues,
            backgroundColor: statusLabels.map(l => statusColors[l] || '#444'),
            borderColor: '#111',
            borderWidth: 3,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 14, font: { size: 11 } } }
        },
        cutout: '65%',
    }
});
</script>
@endpush
