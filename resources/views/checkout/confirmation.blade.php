@extends('layouts.app')
@php $pageTitle = 'Order Confirmed' @endphp
@section('content')

<section class="section" style="min-height:80vh;display:flex;align-items:center;">
    <div class="container">
        <div class="confirm-card">
            <div class="confirm-icon">✅</div>
            <h2 style="color:var(--white);margin-bottom:8px;">Order Confirmed!</h2>
            <p style="margin-bottom:24px;">Thank you for your order. We'll get it ready for you ASAP.</p>

            <div style="background:var(--dark);border:1px solid rgba(232,255,71,0.2);border-radius:4px;padding:20px;margin-bottom:24px;">
                <div style="font-size:0.72rem;color:var(--grey);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:8px;">Order ID</div>
                <div class="confirm-order-id">{{ $order->formatted_id }}</div>
            </div>

            <div style="text-align:left;margin-bottom:24px;">
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:12px;">Items Ordered</div>
                @foreach ($order->items as $item)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.85rem;">
                        <span style="color:var(--text);">{{ $item->variant->product->product_name }} ({{ $item->variant->size }}, {{ $item->variant->colour }}) × {{ $item->quantity }}</span>
                        <span style="color:var(--accent);">RM {{ number_format($item->price_at_purchase * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                <div style="display:flex;justify-content:space-between;padding:12px 0;font-family:'Bebas Neue',sans-serif;font-size:1.4rem;color:var(--accent);">
                    <span>TOTAL</span>
                    <span>RM {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div style="text-align:left;margin-bottom:32px;">
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:12px;">Delivery Details</div>
                <p style="font-size:0.85rem;color:var(--text);line-height:1.7;">
                    {{ $order->delivery_name }}<br>
                    {{ $order->delivery_phone }}<br>
                    {{ $order->delivery_address }}<br>
                    {{ $order->delivery_postcode }}, {{ $order->delivery_state }}
                </p>
            </div>

            <div style="background:rgba(232,255,71,0.08);border:1px solid rgba(232,255,71,0.2);border-radius:4px;padding:16px;margin-bottom:32px;font-size:0.82rem;color:var(--grey);">
                📦 Estimated delivery: 3–7 business days<br>
                💳 Payment: {{ $order->payment_method }}
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Track My Orders</a>
                <a href="{{ route('products.index') }}" class="btn btn-dark">Keep Shopping</a>
            </div>
        </div>
    </div>
</section>
@endsection
