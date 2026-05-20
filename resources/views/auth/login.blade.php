@extends('layouts.app')
@php $pageTitle = 'Login' @endphp
@section('content')

<section class="section" style="min-height:80vh;display:flex;align-items:center;">
    <div class="container">
        <div class="form-card">
            <div class="form-title">Welcome Back</div>
            <p class="form-sub">Sign in to your DRIP CULTURE account.</p>

            @if ($errors->has('email'))
                <div class="flash flash-error">{{ $errors->first('email') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') input-error @enderror"
                           placeholder="your@email.com" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control @error('password') input-error @enderror"
                           placeholder="Your password">
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:var(--accent);">
                    <label for="remember" style="color:var(--grey);font-size:0.85rem;">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">Login →</button>
            </form>

            <div class="form-divider">Don't have an account?</div>
            <a href="{{ route('register') }}" class="btn btn-dark btn-full">Create Account</a>
        </div>
    </div>
</section>
@endsection
