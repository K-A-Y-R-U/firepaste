<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $siteName = \App\Models\GeneralSetting::first()->site_name ?? config('app.name', 'Firepaste'); @endphp
    <title>{{ $siteName }}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/theme.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @livewireStyles

    <style>
        body.auth-page {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            background: #0d0d14 !important;
            display: flex !important;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        body.auth-page .auth-panel-left {
            display: none;
            width: 45%;
            position: relative;
            background: linear-gradient(135deg, #0d0d14 0%, #1a1a2e 50%, #16213e 100%);
            overflow: hidden;
            flex-shrink: 0;
        }

        @media (min-width: 992px) {
            body.auth-page .auth-panel-left {
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 3rem;
            }
        }

        body.auth-page .auth-panel-left::before {
            content: '';
            position: absolute;
            top: -200px; left: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(102,126,234,0.25) 0%, transparent 70%);
            border-radius: 50%;
        }

        body.auth-page .auth-panel-left::after {
            content: '';
            position: absolute;
            bottom: -150px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(118,75,162,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        body.auth-page .left-content { position: relative; z-index: 2; }

        body.auth-page .left-brand {
            margin-bottom: 1rem;
            display: block;
            font-size: 1.8rem;
        }

        body.auth-page .left-tagline {
            color: rgba(255,255,255,0.5);
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 3rem;
            font-weight: 300;
        }

        body.auth-page .feature-list { display: flex; flex-direction: column; gap: 1.25rem; }

        body.auth-page .feature-item { display: flex; align-items: center; gap: 1rem; }

        body.auth-page .feature-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            background: rgba(102,126,234,0.15);
            border: 1px solid rgba(102,126,234,0.3);
            display: flex; align-items: center; justify-content: center;
            color: #667eea;
            font-size: 1rem;
            flex-shrink: 0;
        }

        body.auth-page .feature-text { color: rgba(255,255,255,0.65); font-size: 0.9rem; }

        body.auth-page .grid-decoration {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                linear-gradient(rgba(102,126,234,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(102,126,234,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        body.auth-page .auth-panel-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 1.5rem;
            background: #f5f5f7;
            overflow-y: auto;
        }

        body.auth-page .auth-form-wrapper { width: 100%; max-width: 420px; }

        body.auth-page .auth-logo-mobile {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        body.auth-page .auth-logo-mobile a {
            font-size: 1.4rem;
        }

        @media (min-width: 992px) { body.auth-page .auth-logo-mobile { display: none; } }

        body.auth-page .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.06);
        }

        body.auth-page .auth-card-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #0d0d14;
            margin-bottom: 0.4rem;
            letter-spacing: -0.5px;
        }

        body.auth-page .auth-card-subtitle { color: #888; font-size: 0.9rem; margin-bottom: 2rem; }

        body.auth-page .auth-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        body.auth-page .auth-input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e8e8ee;
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: 'DM Sans', sans-serif;
            color: #0d0d14;
            background: #fafafa;
            transition: all 0.2s ease;
            outline: none;
        }

        body.auth-page .auth-input:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
        }

        body.auth-page .auth-input.is-invalid { border-color: #ef4444; background: #fff5f5; }
        body.auth-page .auth-input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(239,68,68,0.1); }

        body.auth-page .invalid-feedback {
            font-size: 0.8rem; color: #ef4444; margin-top: 0.4rem; display: block;
        }

        body.auth-page .auth-field { margin-bottom: 1.25rem; }

        body.auth-page .auth-check { display: flex; align-items: center; gap: 0.6rem; }
        body.auth-page .auth-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: #667eea; cursor: pointer; }
        body.auth-page .auth-check label { font-size: 0.88rem; color: #666; cursor: pointer; margin: 0; }

        body.auth-page .auth-btn {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        body.auth-page .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }

        body.auth-page .auth-btn:active { transform: translateY(0); }

        body.auth-page .auth-forgot a { font-size: 0.83rem; color: #667eea; text-decoration: none; font-weight: 500; }
        body.auth-page .auth-forgot a:hover { text-decoration: underline; }

        body.auth-page .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: #888; }
        body.auth-page .auth-footer a { color: #667eea; font-weight: 600; text-decoration: none; }
        body.auth-page .auth-footer a:hover { text-decoration: underline; }

        body.auth-page .auth-alert {
            padding: 0.8rem 1rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            color: #16a34a;
            font-size: 0.88rem;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body class="auth-page">

    <div class="auth-panel-left">
        <div class="grid-decoration"></div>
        <div class="left-content">
            <a class="navbar-brand left-brand" href="{{ url('/') }}" wire:navigate>{{ $siteName }}</a>
            <p class="left-tagline">Tu plataforma para compartir<br>contenido de forma rápida<br>y organizada.</p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <span class="feature-text">Publicación instantánea de contenido</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-folder-open"></i></div>
                    <span class="feature-text">Organizado por categorías y catálogos</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-crown"></i></div>
                    <span class="feature-text">Membresías VIP con acceso exclusivo</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <span class="feature-text">Tu cuenta segura y protegida</span>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-panel-right">
        <div class="auth-form-wrapper">
            <div class="auth-logo-mobile">
                <a class="navbar-brand" href="{{ url('/') }}" wire:navigate>{{ $siteName }}</a>
            </div>
            {{ $slot }}
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/bootstrap.min.js') }}"></script>

    {{-- Al navegar a otra página, limpiar estilos del body que dejó auth-page --}}
    <script>
        document.addEventListener('livewire:navigating', function() {
            document.body.classList.remove('auth-page');
            document.body.style.cssText = '';
        });
    </script>

    @livewireScripts
</body>
</html>