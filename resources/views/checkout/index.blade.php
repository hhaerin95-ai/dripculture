@extends('layouts.app')
@php $pageTitle = 'Checkout' @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <h1>Checkout</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> <a href="{{ route('cart.index') }}">Cart</a> <span>/</span> Checkout</div>
    </div>
</div>

<section class="section">
    <div class="container">
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:40px;align-items:start;">

                <!-- Delivery Form -->
                <div>
                    <h3 style="color:var(--white);margin-bottom:24px;">Delivery Information</h3>

                    <div class="form-group">
                        <label class="form-label">Recipient Name *</label>
                        <input type="text" name="delivery_name" class="form-control @error('delivery_name') input-error @enderror"
                               value="{{ old('delivery_name', $user->name) }}">
                        @error('delivery_name') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" name="delivery_phone" class="form-control @error('delivery_phone') input-error @enderror"
                               value="{{ old('delivery_phone', $user->phone) }}">
                        @error('delivery_phone') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Delivery Address *</label>
                        <textarea name="delivery_address" rows="3" class="form-control @error('delivery_address') input-error @enderror">{{ old('delivery_address', $user->address) }}</textarea>
                        @error('delivery_address') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Postcode *</label>
                            <input type="text" name="delivery_postcode" maxlength="5" class="form-control @error('delivery_postcode') input-error @enderror"
                                   value="{{ old('delivery_postcode', $user->postcode) }}">
                            @error('delivery_postcode') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">State *</label>
                            <select name="delivery_state" class="form-control @error('delivery_state') input-error @enderror">
                                <option value="">— Select State —</option>
                                @foreach ($states as $s)
                                    <option value="{{ $s }}" {{ old('delivery_state', $user->state) === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('delivery_state') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method *</label>
                        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:8px;">
                            @foreach (['Cash on Delivery', 'Bank Transfer'] as $pm)
                                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;background:var(--darker);border:1px solid var(--border);border-radius:4px;padding:14px 20px;">
                                    <input type="radio" name="payment_method" value="{{ $pm }}" {{ old('payment_method') === $pm ? 'checked' : '' }} style="accent-color:var(--accent);">
                                    <span style="font-size:0.85rem;font-weight:700;color:var(--white);">{{ $pm }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Order Notes (Optional)</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="cart-summary">
                        <h3 style="color:var(--white);margin-bottom:20px;">Order Summary</h3>
                        @foreach ($cartItems as $item)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);gap:12px;">
                                <div style="display:flex;gap:10px;align-items:center;">
                                    <span style="font-size:1.5rem;">{{ optional($item->variant->product)->emoji ?? '🛍️' }}</span>
                                    <div>
                                        <div style="font-size:0.8rem;font-weight:700;color:var(--white);">{{ optional($item->variant->product)->product_name }}</div>
                                        <div style="font-size:0.72rem;color:var(--grey);">{{ $item->size }} · {{ $item->colour }} × {{ $item->quantity }}</div>
                                    </div>
                                </div>
                                <div style="color:var(--accent);font-weight:700;">RM {{ number_format($item->variant->product->base_price * $item->quantity, 2) }}</div>
                            </div>
                        @endforeach
                        <div class="summary-row" style="margin-top:8px;">
                            <span style="color:var(--grey);">Subtotal</span>
                            <span style="color:var(--white);">RM {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span style="color:var(--grey);">Shipping</span>
                            <span style="color:{{ $shipping === 0 ? 'var(--accent)' : 'var(--white)' }};">{{ $shipping === 0 ? 'FREE' : 'RM ' . number_format($shipping, 2) }}</span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>RM {{ number_format($total, 2) }}</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full" style="margin-top:24px;">Place Order →</button>
                        <a href="{{ route('cart.index') }}" class="btn btn-dark btn-full" style="margin-top:8px;">← Back to Cart</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
