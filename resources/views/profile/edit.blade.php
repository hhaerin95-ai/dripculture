@extends('layouts.app')
@php $pageTitle = 'My Profile' @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <h1>My Profile</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> Profile</div>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:680px;">
        <div style="text-align:center;margin-bottom:40px;">
            <div style="width:80px;height:80px;background:var(--darker);border:2px solid var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;margin:0 auto 16px;">👤</div>
            <h3 style="color:var(--white);">{{ $user->name }}</h3>
            <p style="font-size:0.85rem;">{{ $user->email }}</p>
            <p style="font-size:0.75rem;color:var(--grey);margin-top:4px;">Member since {{ $user->created_at->format('F Y') }}</p>
        </div>

        <div class="form-card" style="max-width:100%;">
            <div class="form-title" style="font-size:1.5rem;">Edit Profile</div>
            <p class="form-sub">Update your personal information and delivery address.</p>

            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') input-error @enderror" value="{{ old('name', $user->name) }}">
                    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled style="opacity:0.5;cursor:not-allowed;">
                    <div style="font-size:0.72rem;color:var(--grey);margin-top:4px;">Email cannot be changed.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number *</label>
                    <input type="tel" name="phone" class="form-control @error('phone') input-error @enderror" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Delivery Address *</label>
                    <textarea name="address" rows="3" class="form-control @error('address') input-error @enderror">{{ old('address', $user->address) }}</textarea>
                    @error('address') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Postcode *</label>
                        <input type="text" name="postcode" maxlength="5" class="form-control @error('postcode') input-error @enderror" value="{{ old('postcode', $user->postcode) }}">
                        @error('postcode') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">State *</label>
                        <select name="state" class="form-control @error('state') input-error @enderror">
                            <option value="">— Select State —</option>
                            @foreach ($states as $s)
                                <option value="{{ $s }}" {{ old('state', $user->state) === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('state') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-full">Save Changes →</button>
            </form>
        </div>

        <div style="display:flex;gap:12px;justify-content:center;margin-top:24px;">
            <a href="{{ route('orders.index') }}" class="btn btn-dark">My Orders</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline">Logout</button>
            </form>
        </div>
    </div>
</section>
@endsection
