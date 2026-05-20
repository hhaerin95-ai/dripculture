@extends('layouts.app')
@php $pageTitle = 'Shopping Cart' @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> Cart</div>
    </div>
</div>

<section class="section">
    <div class="container">
        @if ($cartItems->isEmpty())
            <div style="text-align:center;padding:80px 0;">
                <div style="font-size:5rem;margin-bottom:24px;">🛒</div>
                <h2 style="color:var(--white);margin-bottom:12px;">Your Cart is Empty</h2>
                <p style="margin-bottom:32px;">Looks like you haven't added anything yet. Let's fix that.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping →</a>
            </div>
        @else
            <div class="cart-layout">
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                        <h3 style="color:var(--white);">Cart Items ({{ $cartItems->count() }})</h3>
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Clear entire cart?')">Clear All</button>
                        </form>
                    </div>
                    <table class="cart-table">
                        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr></thead>
                        <tbody>
@foreach ($cartItems as $item)
<tr>
<td>
<div style="display:flex;align-items:center;gap:16px;">
<div style="width:72px;height:72px;background:var(--darker);border:1px solid var(--border);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0;">
{{ optional($item->variant->product)->emoji ?? '🛍️' }}
</div>

<div>
<div class="cart-item-name">
{{ optional($item->variant->product)->product_name ?? 'Product removed' }}
</div>

<div class="cart-item-meta">
Size: {{ $item->variant->size ?? '-' }}
&nbsp;|&nbsp;
Colour: {{ $item->variant->colour ?? '-' }}
</div>
</div>
</div>
</td>

<td style="color:var(--grey);">
RM {{ number_format(optional($item->variant->product)->base_price ?? 0, 2) }}
</td>

<td>
<form method="POST" action="{{ route('cart.update', $item->cart_id) }}" style="display:inline;">
@csrf
<div style="display:flex;gap:4px;align-items:center;">
<input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" class="qty-input">
<button type="submit" style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:0.9rem;">✓</button>
</div>
</form>
</td>

<td style="color:var(--accent);font-weight:700;font-family:'Bebas Neue',sans-serif;font-size:1.2rem;">
RM {{ number_format((optional($item->variant->product)->base_price ?? 0) * $item->quantity, 2) }}
</td>

<td>
<form method="POST" action="{{ route('cart.remove', $item->cart_id) }}">
@csrf
<button type="submit" style="background:none;border:none;color:var(--grey);cursor:pointer;font-size:1.2rem;">✕</button>
</form>
</td>

</tr>
@endforeach
</tbody>
                    </table>
                </div>

                <div>
                    <div class="cart-summary">
                        <h3 style="color:var(--white);margin-bottom:20px;">Order Summary</h3>
                        <div class="summary-row">
                            <span style="color:var(--grey);">Subtotal</span>
                            <span style="color:var(--white);">RM {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span style="color:var(--grey);">Shipping</span>
                            <span style="color:{{ $shipping === 0 ? 'var(--accent)' : 'var(--white)' }};">
                                {{ $shipping === 0 ? 'FREE ✅' : 'RM ' . number_format($shipping, 2) }}
                            </span>
                        </div>
                        @if ($shipping > 0)
                            <div style="font-size:0.75rem;color:var(--grey);text-align:right;margin-top:4px;">Add RM {{ number_format(150 - $subtotal, 2) }} more for free shipping</div>
                        @endif
                        <div class="summary-total">
                            <span>Total</span>
                            <span>RM {{ number_format($total, 2) }}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-full" style="margin-top:24px;">Proceed to Checkout →</a>
                        <a href="{{ route('products.index') }}" class="btn btn-dark btn-full" style="margin-top:8px;">Continue Shopping</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
