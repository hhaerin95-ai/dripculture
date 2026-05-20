@extends('admin.layouts.app')
@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">{{ $orders->total() }} total orders</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn-outline-dim">
        <i class="bi bi-bar-chart-line"></i> View Report
    </a>
</div>

{{-- Status quick-filter tabs --}}
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
    @php
        $statuses = ['', 'Pending', 'Processing', 'Packed', 'Shipped', 'Delivered', 'Cancelled'];
        $labels   = ['All', 'Pending', 'Processing', 'Packed', 'Shipped', 'Delivered', 'Cancelled'];
    @endphp
    @foreach($statuses as $k => $s)
    <a href="{{ route('admin.orders.index') }}?status={{ $s }}&date_from={{ request('date_from') }}&date_to={{ request('date_to') }}"
       class="btn-outline-dim {{ request('status', '') === $s ? 'active' : '' }}"
       style="{{ request('status', '') === $s ? 'border-color:var(--accent);color:var(--accent);' : '' }}">
        {{ $labels[$k] }}
        @if($s && isset($statusCounts[$s]))
            <span style="background:var(--border);padding:1px 6px;border-radius:99px;font-size:10px;margin-left:4px;">{{ $statusCounts[$s] }}</span>
        @endif
    </a>
    @endforeach
</div>

{{-- Filter bar --}}
<div class="card-dark mb-4">
    <div class="card-body-dark">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div style="flex:1;min-width:200px;">
                <label class="form-label-dark">Search</label>
                <input name="search" type="text" class="form-control-dark" placeholder="Order ID or customer name..." value="{{ request('search') }}">
            </div>
            <div style="min-width:140px;">
                <label class="form-label-dark">Date From</label>
                <input name="date_from" type="date" class="form-control-dark" value="{{ request('date_from') }}">
            </div>
            <div style="min-width:140px;">
                <label class="form-label-dark">Date To</label>
                <input name="date_to" type="date" class="form-control-dark" value="{{ request('date_to') }}">
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;padding-bottom:1px;">
                <button type="submit" class="btn-accent"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn-outline-dim">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Orders table --}}
<div class="card-dark">
    <div style="overflow-x:auto;">
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="mono" style="color:var(--accent);">#{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div style="font-weight:500;color:var(--text-primary);">{{ $order->user->full_name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $order->user->email ?? '' }}</div>
                    </td>
                    <td class="mono">{{ $order->items_count }}</td>
                    <td class="mono">RM {{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        @if($order->payment)
                            <span class="badge-status badge-{{ strtolower($order->payment->payment_status) }}" style="font-size:10px;">
                                {{ $order->payment->payment_status }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($order->order_status) }}">
                            {{ $order->order_status }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:12px;">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline-dim" style="font-size:11px;padding:5px 12px;">
                            View <i class="bi bi-arrow-right"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state"><i class="bi bi-bag-x"></i><p>No orders found.</p></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
