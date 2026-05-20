<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? config('app.name') }} — DRIP CULTURE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="navbar-logo">DRIP CULTUR<span>E</span></a>
        
        <div class="navbar-links" id="navLinks">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Shop</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
        </div>

        <div class="navbar-actions">
            @auth
                <a href="{{ route('cart.index') }}" class="cart-btn">
                    🛒 <span class="cart-count">0</span>
                </a>
                <div class="nav-dropdown" style="position:relative;">
                    <span class="navbar-logo" style="font-size:1rem;cursor:pointer;">
                        {{ explode(' ', auth()->user()->name)[0] }} ▾
                    </span>
                    <div class="nav-dropdown-menu" style="display:none;position:absolute;right:0;top:100%;background:var(--dark);border:1px solid var(--border);border-radius:var(--radius);padding:8px;min-width:160px;z-index:999;">
                        <a href="{{ route('profile.edit') }}" style="display:block;padding:10px 14px;font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--light-grey);transition:color var(--transition);" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--light-grey)'">My Profile</a>
                        <a href="{{ route('orders.index') }}" style="display:block;padding:10px 14px;font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--light-grey);transition:color var(--transition);" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--light-grey)'">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="width:100%;text-align:left;padding:10px 14px;background:none;border:none;font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--accent-2);cursor:pointer;">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
            @endauth
        </div>

        <button class="hamburger" onclick="document.getElementById('navLinks').classList.toggle('open')">☰</button>
    </div>
</nav>

<!-- FLASH MESSAGES -->
@if (session('success'))
    <div class="flash flash-success">{{ session('success') }}</div>
@endif
@if (session('info'))
    <div class="flash flash-info">{{ session('info') }}</div>
@endif
@if (session('error'))
    <div class="flash flash-error">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="flash flash-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<!-- CONTENT -->
@yield('content')

<!-- FOOTER -->
<footer class="footer">
    <div class="container footer-inner">
        <div>
            <div class="footer-brand">DRIP CULTURE</div>
            <p style="color:var(--grey); font-size:0.85rem; margin-top:8px;">Wear Your Identity.</p>
        </div>
        <div class="footer-links">
            <a href="{{ route('products.index') }}">Shop</a>
            <a href="{{ route('contact') }}">Contact</a>
            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endguest
        </div>
        <div style="font-size:0.78rem; color:var(--grey); margin-top:16px; text-align:center;">
            &copy; {{ date('Y') }} DRIP CULTURE. All rights reserved.
        </div>
    </div>
</footer>
<script>
    // Close dropdown bila click luar
    document.addEventListener('click', function(e) {
        const dropdown = document.querySelector('.nav-dropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            document.querySelector('.nav-dropdown-menu').style.display = 'none';
        }
    });

    // Toggle dropdown
    document.querySelector('.nav-dropdown span')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const menu = document.querySelector('.nav-dropdown-menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });
</script>

</body>
</html>
