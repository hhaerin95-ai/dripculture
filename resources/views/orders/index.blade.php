@extends('layouts.app')
@php $pageTitle = 'My Orders' @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <h1>My Orders</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> My Orders</div>
    </div>
</div>

<section class="section">
    <div class="container">
        @if ($orders->isEmpty())
            <div style="text-align:center;padding:80px 0;">
                <div style="font-size:4rem;margin-bottom:24px;">📦</div>
                <h2 style="color:var(--white);margin-bottom:12px;">No Orders Yet</h2>
                <p style="margin-bottom:32px;">You haven't placed any orders. Time to start shopping!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now →</a>
            </div>
        @else
            <p style="color:var(--grey);font-size:0.85rem;margin-bottom:24px;">
                Total orders: <strong style="color:var(--accent)">{{ $orders->count() }}</strong>
            </p>
            @foreach ($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">{{ $order->formatted_id }}</div>
                            <div class="order-date">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</div>
                            <div style="font-size:0.78rem;color:var(--grey);margin-top:4px;">{{ $order->items_count }} item(s) · {{ $order->payment_method }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div class="order-total">RM {{ number_format($order->total_amount, 2) }}</div>
                            <div style="margin-top:8px;"><span class="badge {{ $order->status_badge_class }}">{{ $order->status }}</span></div>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                        <div style="font-size:0.78rem;color:var(--grey);">📍 {{ $order->delivery_address }}, {{ $order->delivery_state }}</div>
                        <a href="{{ route('orders.show', $order->order_id) }}" class="btn btn-dark btn-sm">View Details →</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
