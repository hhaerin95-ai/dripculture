<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — DRIP CULTURE Admin</title>

    {{-- Google Fonts: Space Mono (monospace) + DM Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* ──────────────────────────────────────────────────────────────
           DRIP CULTURE ADMIN — Design System
           Aesthetic: Industrial dark, editorial grid, bold monospace
        ────────────────────────────────────────────────────────────── */
        :root {
            --bg-base:       #0a0a0a;
            --bg-surface:    #111111;
            --bg-elevated:   #1a1a1a;
            --bg-card:       #141414;
            --border:        #2a2a2a;
            --border-bright: #3a3a3a;
            --text-primary:  #f0f0f0;
            --text-secondary:#888888;
            --text-muted:    #555555;
            --accent:        #e8ff00;       /* neon yellow — drip accent */
            --accent-dim:    #b8cc00;
            --accent-hover:  #f5ff4d;
            --danger:        #ff4545;
            --success:       #00e676;
            --warning:       #ffb300;
            --info:          #40c4ff;
            --sidebar-w:     260px;
            --topbar-h:      64px;
            --font-display:  'Space Mono', monospace;
            --font-body:     'DM Sans', sans-serif;
            --radius:        4px;
            --transition:    .18s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: var(--bg-base);
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ── SIDEBAR ──────────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand .brand-name {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 0.05em;
            line-height: 1;
        }
        .sidebar-brand .brand-sub {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }
        .nav-section-label {
            font-size: 9px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 12px 8px 6px;
            font-family: var(--font-display);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all var(--transition);
            margin-bottom: 2px;
            position: relative;
        }
        .sidebar-link:hover {
            color: var(--text-primary);
            background: var(--bg-elevated);
        }
        .sidebar-link.active {
            color: var(--accent);
            background: rgba(232,255,0,0.07);
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; height: 60%;
            width: 2px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }
        .sidebar-link i { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 99px;
            font-family: var(--font-display);
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }
        .admin-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }
        .admin-avatar {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 700;
            font-size: 13px;
            font-family: var(--font-display);
            flex-shrink: 0;
        }
        .admin-name  { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .admin-role  { font-size: 11px; color: var(--text-muted); }

        /* ── TOPBAR ───────────────────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 900;
        }
        .topbar-title {
            font-family: var(--font-display);
            font-size: 13px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--text-secondary);
        }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-clock {
            font-family: var(--font-display);
            font-size: 12px;
            color: var(--text-muted);
        }

        /* ── MAIN CONTENT ─────────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 32px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ── PAGE HEADER ──────────────────────────────────────────── */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        .page-title {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 0.05em;
        }
        .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ── CARDS ────────────────────────────────────────────────── */
        .card-dark {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .card-dark .card-header-dark {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            font-family: var(--font-display);
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-secondary);
        }
        .card-dark .card-body-dark { padding: 20px; }

        /* Stat cards */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            transition: border-color var(--transition);
        }
        .stat-card:hover { border-color: var(--border-bright); }
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: var(--accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
        }
        .stat-card:hover::after { transform: scaleX(1); }
        .stat-label {
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-muted);
            font-family: var(--font-display);
            margin-bottom: 8px;
        }
        .stat-value {
            font-family: var(--font-display);
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }
        .stat-icon {
            position: absolute;
            top: 20px; right: 20px;
            font-size: 28px;
            color: var(--border-bright);
        }
        .stat-change {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        /* ── TABLES ───────────────────────────────────────────────── */
        .table-dark-custom {
            width: 100%;
            border-collapse: collapse;
        }
        .table-dark-custom thead th {
            padding: 10px 16px;
            text-align: left;
            font-family: var(--font-display);
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .table-dark-custom tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 13.5px;
            vertical-align: middle;
        }
        .table-dark-custom tbody tr:hover td {
            background: var(--bg-elevated);
            color: var(--text-primary);
        }
        .table-dark-custom tbody tr:last-child td { border-bottom: none; }

        /* ── BUTTONS ──────────────────────────────────────────────── */
        .btn-accent {
            background: var(--accent);
            color: #000;
            border: none;
            padding: 8px 18px;
            border-radius: var(--radius);
            font-family: var(--font-display);
            font-size: 12px;
            letter-spacing: 0.1em;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-accent:hover {
            background: var(--accent-hover);
            color: #000;
            transform: translateY(-1px);
        }
        .btn-outline-dim {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            padding: 7px 16px;
            border-radius: var(--radius);
            font-family: var(--font-display);
            font-size: 11px;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-outline-dim:hover {
            border-color: var(--text-secondary);
            color: var(--text-primary);
        }
        .btn-danger-dim {
            background: rgba(255,69,69,0.1);
            color: var(--danger);
            border: 1px solid rgba(255,69,69,0.3);
            padding: 7px 16px;
            border-radius: var(--radius);
            font-size: 12px;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-danger-dim:hover {
            background: rgba(255,69,69,0.2);
            color: var(--danger);
        }

        /* ── BADGES / STATUS ──────────────────────────────────────── */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: var(--radius);
            font-size: 11px;
            font-family: var(--font-display);
            letter-spacing: 0.08em;
            font-weight: 700;
        }
        .badge-status::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            background: currentColor;
        }
        .badge-pending    { background: rgba(255,179,0,.12);  color: var(--warning); }
        .badge-processing { background: rgba(64,196,255,.12); color: var(--info); }
        .badge-packed     { background: rgba(153,102,255,.12);color: #9966ff; }
        .badge-shipped    { background: rgba(0,230,118,.12);  color: var(--success); }
        .badge-delivered  { background: rgba(0,230,118,.2);   color: var(--success); }
        .badge-cancelled  { background: rgba(255,69,69,.12);  color: var(--danger); }
        .badge-active     { background: rgba(0,230,118,.12);  color: var(--success); }
        .badge-inactive   { background: rgba(255,69,69,.12);  color: var(--danger); }
        .badge-successful { background: rgba(0,230,118,.12);  color: var(--success); }
        .badge-failed     { background: rgba(255,69,69,.12);  color: var(--danger); }

        /* ── FORMS ────────────────────────────────────────────────── */
        .form-control-dark,
        .form-select-dark {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            padding: 9px 12px;
            font-size: 13.5px;
            font-family: var(--font-body);
            width: 100%;
            transition: border-color var(--transition);
        }
        .form-control-dark:focus,
        .form-select-dark:focus {
            outline: none;
            border-color: var(--accent);
            background: var(--bg-elevated);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(232,255,0,.1);
        }
        .form-control-dark::placeholder { color: var(--text-muted); }
        .form-select-dark option { background: var(--bg-elevated); }

        .form-label-dark {
            font-size: 11px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--text-muted);
            font-family: var(--font-display);
            display: block;
            margin-bottom: 6px;
        }

        /* ── ALERTS ───────────────────────────────────────────────── */
        .alert-dark {
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 13.5px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }
        .alert-success-dark { background: rgba(0,230,118,.1);  border: 1px solid rgba(0,230,118,.25); color: var(--success); }
        .alert-error-dark   { background: rgba(255,69,69,.1);  border: 1px solid rgba(255,69,69,.25); color: var(--danger); }
        .alert-warning-dark { background: rgba(255,179,0,.1);  border: 1px solid rgba(255,179,0,.25); color: var(--warning); }

        /* ── PAGINATION ───────────────────────────────────────────── */
        .pagination .page-link {
            background: var(--bg-elevated);
            border-color: var(--border);
            color: var(--text-secondary);
            font-family: var(--font-display);
            font-size: 12px;
        }
        .pagination .page-link:hover {
            background: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-bright);
        }
        .pagination .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: #000;
        }

        /* ── MISC ─────────────────────────────────────────────────── */
        .divider { border: none; border-top: 1px solid var(--border); margin: 24px 0; }
        .text-accent { color: var(--accent) !important; }
        .text-dim    { color: var(--text-muted) !important; }
        .mono        { font-family: var(--font-display); }
        .product-thumb {
            width: 44px; height: 44px;
            object-fit: cover;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-state i { font-size: 48px; margin-bottom: 16px; display: block; }
        .empty-state p { font-size: 14px; }

        /* scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--border-bright); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ──────────────────────────────────────────────────────── --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-name">DRIP CULTUR<span style="color:var(--accent)">E</span></div>
        <div class="brand-sub">Admin Control Panel</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="nav-section-label">Catalogue</div>
        <a href="{{ route('admin.products.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
        </a>
        <a href="{{ route('admin.inventory.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
            <i class="bi bi-stack"></i> Inventory
        </a>

        <div class="nav-section-label">Commerce</div>
        <a href="{{ route('admin.orders.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> Orders
            @php $pendingCount = \App\Models\Order::where('order_status','Pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="sidebar-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payments.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Payments
            @php $pendingPayments = \App\Models\Payment::where('payment_status','Pending')->count(); @endphp
            @if($pendingPayments > 0)
                <span class="sidebar-badge">{{ $pendingPayments }}</span>
            @endif
        </a>

        <div class="nav-section-label">Analytics</div>
        <a href="{{ route('admin.reports.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Sales Report
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-meta">
            <div class="admin-avatar">{{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}</div>
            <div>
                <div class="admin-name">{{ session('admin_name', 'Administrator') }}</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="sidebar-link" style="width:100%;background:none;border:none;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> Log Out
            </button>
        </form>
    </div>
</aside>

{{-- ── TOPBAR ────────────────────────────────────────────────────────── --}}
<header class="topbar">
    <span class="topbar-title">@yield('page_title', 'Dashboard')</span>
    <div class="topbar-right">
        <span class="topbar-clock" id="clock"></span>
        <a href="{{ route('admin.orders.index') }}?status=Pending" class="btn-outline-dim" style="font-size:11px;">
            <i class="bi bi-bell"></i>
            @if($pendingCount ?? 0 > 0) <span style="color:var(--warning)">{{ $pendingCount ?? 0 }}</span> @endif
        </a>
    </div>
</header>

{{-- ── MAIN ──────────────────────────────────────────────────────────── --}}
<main class="main-content">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert-dark alert-success-dark">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-dark alert-error-dark">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Live clock
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent =
            now.toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Auto-dismiss flash alerts
    document.querySelectorAll('.alert-dark').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
</script>

@stack('scripts')
</body>
</html>