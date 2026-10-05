<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator login · Preventia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-page">
    <div class="login-orbit orbit-one"></div>
    <div class="login-orbit orbit-two"></div>
    <main class="login-layout">
        <section class="login-intro">
            <div class="brand brand-large">
                <div class="brand-mark"><span></span><span></span><span></span></div>
                <div>
                    <strong>preventia</strong>
                    <small>field intelligence</small>
                </div>
            </div>
            <p class="eyebrow">ADMINISTRATIVE CONTROL CENTER</p>
            <h1>Make every field visit <em>count.</em></h1>
            <p class="login-description">A secure view of pharmaceutical field activity, built for teams that need clarity on every visit, territory, and request.</p>
            <div class="login-proof">
                <div class="proof-line"><span class="proof-check">✓</span><span>GPS-verified attendance</span></div>
                <div class="proof-line"><span class="proof-check">✓</span><span>Immutable visit records</span></div>
                <div class="proof-line"><span class="proof-check">✓</span><span>Predictive territory insights</span></div>
            </div>
        </section>

        <section class="login-card">
            <div class="login-card-header">
                <span class="status-pill"><span class="status-pulse"></span> Administrator access</span>
                <span class="mono-label">PREV / 01</span>
            </div>
            <h2>Welcome back, <span>Nica.</span></h2>
            <p class="login-card-copy">Sign in to review today’s field activity.</p>
            @if($errors->any())
                <div class="form-error" role="alert">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}" class="login-form">
                @csrf
                <label for="username">Administrator username</label>
                <div class="input-wrap">
                    <span class="input-prefix">@</span>
                    <input id="username" name="username" type="text" value="{{ old('username', 'nica') }}" autocomplete="username" required autofocus>
                </div>
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="input-prefix">••</span>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                    <button type="button" class="password-toggle" data-toggle-password aria-label="Show password">Show</button>
                </div>
                <button class="primary-button login-button" type="submit">Enter dashboard <span>→</span></button>
            </form>
            <div class="login-footer">
                <span class="lock-mark">▣</span>
                <span>Administrator-only access · Preventia Healthcare</span>
            </div>
        </section>
    </main>
</body>
</html>