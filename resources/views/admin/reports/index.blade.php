@extends('admin.layouts.app')
@section('title', 'Sales Report')
@section('page_title', 'Reports')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Sales Report</h1>
        <p class="page-subtitle">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.reports.export.csv', ['date_from'=>$dateFrom,'date_to'=>$dateTo,'status'=>$status]) }}"
           class="btn-outline-dim"><i class="bi bi-filetype-csv"></i> Export CSV</a>
        <a href="{{ route('admin.reports.export.pdf', ['date_from'=>$dateFrom,'date_to'=>$dateTo,'status'=>$status]) }}"
           target="_blank" class="btn-outline-dim"><i class="bi bi-printer"></i> Print PDF</a>
    </div>
</div>

{{-- Filter --}}
<div class="card-dark mb-4">
    <div class="card-body-dark">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div style="min-width:140px;">
                <label class="form-label-dark">Date From</label>
                <input name="date_from" type="date" class="form-control-dark" value="{{ $dateFrom }}">
            </div>
            <div style="min-width:140px;">
                <label class="form-label-dark">Date To</label>
                <input name="date_to" type="date" class="form-control-dark" value="{{ $dateTo }}">
            </div>
            <div style="min-width:150px;">
                <label class="form-label-dark">Order Status</label>
                <select name="status" class="form-select-dark">
                    <option value="">All Status</option>
                    @foreach(['Pending','Processing','Packed','Shipped','Delivered','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;padding-bottom:1px;">
                <button type="submit" class="btn-accent"><i class="bi bi-search"></i> Generate</button>
            </div>
        </form>
    </div>
</div>

{{-- Summary stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <i class="bi bi-bag-check stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value" style="font-size:20px;">RM {{ number_format($totalRevenue, 2) }}</div>
            <i class="bi bi-currency-dollar stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Delivered</div>
            <div class="stat-value" style="color:var(--success);">{{ $deliveredCount }}</div>
            <i class="bi bi-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Cancelled</div>
            <div class="stat-value" style="color:var(--danger);">{{ $cancelledCount }}</div>
            <i class="bi bi-x-circle stat-icon"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Orders table --}}
    <div class="col-12 col-lg-8">
        <div class="card-dark">
            <div class="card-header-dark">Order List</div>
            <div style="overflow-x:auto;">
                <table class="table-dark-custom">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="mono" style="color:var(--accent);">
                                <a href="{{ route('admin.orders.show', $order) }}" style="color:inherit;text-decoration:none;">
                                    #{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $order->user->full_name ?? 'N/A' }}</td>
                            <td style="color:var(--text-muted);font-size:12px;">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                            <td style="font-size:12px;">{{ $order->payment?->payment_method ?? '—' }}</td>
                            <td>
                                <span class="badge-status badge-{{ strtolower($order->order_status) }}" style="font-size:10px;">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="mono">RM {{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-bar-chart"></i><p>No orders for this period.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top products --}}
    <div class="col-12 col-lg-4">
        <div class="card-dark">
            <div class="card-header-dark">Top Selling Variants</div>
            <div class="card-body-dark" style="padding:0;">
                @forelse($topProducts as $i => $item)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border);">
                    <div class="mono" style="font-size:20px;color:var(--text-muted);width:24px;text-align:right;">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;color:var(--text-primary);">
                            {{ Str::limit($item->variant?->product?->product_name ?? 'N/A', 22) }}
                        </div>
                        <div style="font-size:11px;color:var(--text-muted);">
                            {{ $item->variant?->size }} / {{ $item->variant?->colour }}
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div class="mono" style="font-size:14px;font-weight:700;color:var(--accent);">{{ $item->total_sold }}</div>
                        <div style="font-size:10px;color:var(--text-muted);">sold</div>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding:32px;"><i class="bi bi-trophy" style="font-size:32px;"></i><p>No data yet.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
