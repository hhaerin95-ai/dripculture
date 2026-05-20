@extends('layouts.app')
@php $pageTitle = 'Order ' . $order->formatted_id @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <h1>Order {{ $order->formatted_id }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> <span>/</span>
            <a href="{{ route('orders.index') }}">My Orders</a> <span>/</span>
            {{ $order->formatted_id }}
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px;">

            <!-- Order Summary -->
            <div style="background:var(--darker);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:16px;">Order Summary</div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="color:var(--grey);font-size:0.85rem;">Order ID</span>
                    <span style="color:var(--accent);font-weight:700;">{{ $order->formatted_id }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="color:var(--grey);font-size:0.85rem;">Date Placed</span>
                    <span style="color:var(--text);font-size:0.85rem;">{{ $order->order_date->format('d M Y, h:i A') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="color:var(--grey);font-size:0.85rem;">Status</span>
                    <span class="badge {{ $order->status_badge_class }}">{{ $order->order_status }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="color:var(--grey);font-size:0.85rem;">Payment</span>
                    <span style="color:var(--text);font-size:0.85rem;">{{ $order->payment_method }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;border-top:1px solid var(--border);padding-top:12px;margin-top:12px;">
                    <span style="color:var(--white);font-weight:700;">Total</span>
                    <span style="color:var(--accent);font-family:'Bebas Neue',sans-serif;font-size:1.4rem;">RM {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Delivery Details -->
            <div style="background:var(--darker);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:16px;">Delivery Details</div>
                @if($order->address)
                    <p style="color:var(--text);font-size:0.85rem;line-height:1.9;margin:0;">
                        <strong style="color:var(--white);">{{ $order->address->recipient_name }}</strong><br>
                        {{ $order->address->phone_number }}<br>
                        {{ $order->address->address_line }}<br>
                        {{ $order->address->postcode }}, {{ $order->address->state }}
                    </p>
                @else
                    <p style="color:var(--grey);font-size:0.85rem;">No delivery info available.</p>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div style="background:var(--darker);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:32px;">
            <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:16px;">Items Ordered</div>
            @foreach ($order->items as $item)
                <div style="display:flex;align-items:center;gap:16px;padding:16px 0;border-bottom:1px solid var(--border);">
                    <div style="width:56px;height:56px;background:var(--dark);border-radius:4px;overflow:hidden;flex-shrink:0;">
                        @php $img = $item->variant->product->images->first(); @endphp
                        @if($img)
                            <img src="{{ asset('storage/' . $img->image_url) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">👕</div>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div style="color:var(--white);font-weight:700;font-size:0.9rem;">{{ $item->variant->product->product_name }}</div>
                        <div style="color:var(--grey);font-size:0.78rem;margin-top:4px;">{{ $item->variant->size }} / {{ $item->variant->colour }} × {{ $item->quantity }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="color:var(--accent);font-weight:700;">RM {{ number_format($item->price_at_purchase * $item->quantity, 2) }}</div>
                        <div style="color:var(--grey);font-size:0.75rem;">RM {{ number_format($item->price_at_purchase, 2) }} each</div>
                    </div>
                </div>
            @endforeach

            <!-- Totals -->
            <div style="padding-top:16px;">
                @php
                    $subtotal = $order->items->sum(fn($i) => $i->price_at_purchase * $i->quantity);
                    $shipping = $order->total_amount - $subtotal;
                @endphp
                <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--grey);margin-bottom:8px;">
                    <span>Subtotal</span>
                    <span>RM {{ number_format($subtotal, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--grey);margin-bottom:12px;">
                    <span>Shipping</span>
                    <span>{{ $shipping > 0 ? 'RM ' . number_format($shipping, 2) : 'FREE' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-family:'Bebas Neue',sans-serif;font-size:1.4rem;color:var(--accent);border-top:1px solid var(--border);padding-top:12px;">
                    <span>TOTAL</span>
                    <span>RM {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <a href="{{ route('orders.index') }}" class="btn btn-dark">← Back to Orders</a>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping →</a>
        </div>
    </div>
</section>
@endsection
