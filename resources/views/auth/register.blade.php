@extends('layouts.app')
@php $pageTitle = 'Register' @endphp
@section('content')

<section class="section" style="min-height:80vh;display:flex;align-items:center;">
    <div class="container">
        <div class="form-card">
            <div class="form-title">Join The Culture</div>
            <p class="form-sub">Create your DRIP CULTURE account to start shopping.</p>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') input-error @enderror"
                           placeholder="e.g. Ahmad Haziq" value="{{ old('name') }}">
                    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') input-error @enderror"
                           placeholder="your@email.com" value="{{ old('email') }}">
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control @error('password') input-error @enderror"
                               placeholder="Min 8 chars, 1 uppercase, 1 number">
                        @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat your password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number *</label>
                    <input type="tel" name="phone" class="form-control @error('phone') input-error @enderror"
                           placeholder="e.g. 0123456789" value="{{ old('phone') }}">
                    @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Delivery Address *</label>
                    <textarea name="address" rows="3" class="form-control @error('address') input-error @enderror"
                              placeholder="No, Jalan, Taman...">{{ old('address') }}</textarea>
                    @error('address') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Postcode *</label>
                        <input type="text" name="postcode" maxlength="5" class="form-control @error('postcode') input-error @enderror"
                               placeholder="e.g. 41000" value="{{ old('postcode') }}">
                        @error('postcode') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">State *</label>
                        <select name="state" class="form-control @error('state') input-error @enderror">
                            <option value="">— Select State —</option>
                            @foreach ($states as $s)
                                <option value="{{ $s }}" {{ old('state') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('state') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">Create Account →</button>
            </form>

            <div class="form-divider">Already have an account?</div>
            <a href="{{ route('login') }}" class="btn btn-dark btn-full">Login Instead</a>
        </div>
    </div>
</section>
@endsection
