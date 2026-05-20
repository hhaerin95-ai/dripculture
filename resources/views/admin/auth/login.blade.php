<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — DRIP CULTURE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:      #0a0a0a;
            --surface: #111111;
            --border:  #2a2a2a;
            --accent:  #e8ff00;
            --text:    #f0f0f0;
            --muted:   #666;
            --danger:  #ff4545;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: grid;
            place-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* Accent glow */
        body::after {
            content: '';
            position: fixed;
            top: -200px; right: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(232,255,0,.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .brand {
            text-align: center;
            margin-bottom: 40px;
        }
        .brand-logo {
            font-family: 'Space Mono', monospace;
            font-size: 28px;
            color: var(--accent);
            letter-spacing: 0.1em;
            line-height: 1;
        }
        .brand-tagline {
            font-size: 11px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 6px;
        }

        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 36px;
        }

        .login-title {
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 28px;
            text-align: center;
        }

        .field { margin-bottom: 20px; }
        .field label {
            display: block;
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--muted);
            font-family: 'Space Mono', monospace;
            margin-bottom: 8px;
        }
        .field input {
            width: 100%;
            background: #1a1a1a;
            border: 1px solid var(--border);
            border-radius: 4px;
            color: var(--text);
            padding: 11px 14px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            transition: border-color .15s;
        }
        .field input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232,255,0,.08);
        }
        .field input::placeholder { color: #444; }

        .error-msg {
            background: rgba(255,69,69,.1);
            border: 1px solid rgba(255,69,69,.3);
            color: var(--danger);
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login {
            width: 100%;
            background: var(--accent);
            color: #000;
            border: none;
            padding: 13px;
            border-radius: 4px;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.15em;
            cursor: pointer;
            transition: all .15s;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .btn-login:hover {
            background: #f5ff4d;
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
            color: var(--muted);
        }

        /* Validation errors */
        .field-error { font-size: 11px; color: var(--danger); margin-top: 4px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="brand">
            <div class="brand-logo">DRIP CULTUR<span style="color:var(--accent, #e8ff00)">E</span></div>
            <div class="brand-tagline">Admin Control Panel</div>
        </div>

        <div class="login-card">
            <div class="login-title">Admin Access</div>

            @if(session('error'))
                <div class="error-msg">
                    &#9888; {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-msg">
                    &#9888; {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="field">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@dripculture.com"
                        autocomplete="email"
                        required
                    >
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Enter Panel</button>
            </form>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} DRIP CULTURE — Authorised Personnel Only
        </div>
    </div>
</body>
</html>
