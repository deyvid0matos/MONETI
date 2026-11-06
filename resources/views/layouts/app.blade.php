<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="app-loading">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Moneti</title>

    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=4">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=4">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=4">

    <link rel="manifest" href="/manifest.json?v=4">
    <meta name="theme-color" content="#1f1f1f" id="theme-color-meta">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Moneti">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        (function () {
            const savedTheme = localStorage.getItem('moneti_theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.add('theme-light');
            } else {
                document.documentElement.classList.add('theme-dark');
            }
        })();
    </script>

    <style>
        :root {
            --bg-main: #1f1f1f;
            --bg-panel: #262626;
            --bg-card: #2f2f2f;
            --bg-soft: #444444;
            --primary: #12cbe6;
            --primary-hover: #0fb8d0;
            --income: #33d16b;
            --expense: #ff5a52;
            --warning: #ffab4d;
            --text-main: #ffffff;
            --text-soft: #b9b9b9;
            --border-soft: rgba(255,255,255,0.08);
            --shadow-main: 0 14px 34px rgba(0,0,0,.28);
            --body-gradient: linear-gradient(180deg, #1c1c1c 0%, #202020 100%);
            --shell-gradient: linear-gradient(180deg, #262626 0%, #2b2b2b 100%);
            --card-gradient: linear-gradient(180deg, #343434 0%, #2f2f2f 100%);
            --sheet-gradient: linear-gradient(180deg, #2a2a2a 0%, #242424 100%);
            --header-bg: rgba(38,38,38,.96);
            --input-bg: #535353;
            --input-text: #ffffff;
            --placeholder: #d0d0d0;
            --header-btn-bg: rgba(255,255,255,.04);
            --header-btn-border: rgba(255,255,255,.10);
            --header-btn-hover: rgba(255,255,255,.08);
            --install-secondary-bg: rgba(255,255,255,.08);
            --install-secondary-border: rgba(255,255,255,.10);
            --install-secondary-text: #fff;
            --sheet-handle-bg: rgba(255,255,255,.18);
        }

        .theme-light {
            --bg-main: #eef3f8;
            --bg-panel: #ffffff;
            --bg-card: #ffffff;
            --bg-soft: #eef2f7;
            --primary: #0ea5c6;
            --primary-hover: #0b8ca9;
            --income: #169b4a;
            --expense: #dc4c3f;
            --warning: #d9891b;
            --text-main: #1b2430;
            --text-soft: #667085;
            --border-soft: rgba(15,23,42,0.08);
            --shadow-main: 0 14px 34px rgba(15,23,42,.10);
            --body-gradient: linear-gradient(180deg, #f7f9fc 0%, #eef3f8 100%);
            --shell-gradient: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
            --card-gradient: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
            --sheet-gradient: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            --header-bg: rgba(255,255,255,.92);
            --input-bg: #ffffff;
            --input-text: #1b2430;
            --placeholder: #98a2b3;
            --header-btn-bg: rgba(15,23,42,.04);
            --header-btn-border: rgba(15,23,42,.10);
            --header-btn-hover: rgba(15,23,42,.08);
            --install-secondary-bg: rgba(15,23,42,.06);
            --install-secondary-border: rgba(15,23,42,.10);
            --install-secondary-text: #1b2430;
            --sheet-handle-bg: rgba(15,23,42,.18);
        }

        * {
            -webkit-tap-highlight-color: transparent;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background: var(--bg-main);
        }

        body {
            color: var(--text-main);
            font-family: 'Inter', Arial, sans-serif;
            font-weight: 400;
            letter-spacing: -0.005em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            background: var(--body-gradient);
        }

        html.app-loading body {
            opacity: 0;
            visibility: hidden;
        }

        html.app-ready body {
            opacity: 1;
            visibility: visible;
            transition: opacity .18s ease;
        }

        svg {
            display: inline-block;
            vertical-align: middle;
            flex-shrink: 0;
        }

        .app-shell {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--shell-gradient);
            box-shadow: 0 0 0 1px rgba(255,255,255,.03);
            position: relative;
            overflow-x: hidden;
        }

        .theme-light .app-shell {
            box-shadow: 0 0 0 1px rgba(15,23,42,.04);
        }

        .app-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--header-bg);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border-soft);
            padding: 14px 18px;
        }

        .app-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .brand-badge {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            border: none;
        }

        .brand-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 0;
        }

        .brand-name {
            font-size: 1.32rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
            color: var(--text-main);
            line-height: 1;
        }

        .brand-sub {
            margin: 3px 0 0 0;
            color: var(--text-soft);
            font-size: .82rem;
            font-weight: 400;
            line-height: 1.15;
            letter-spacing: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .header-link {
            text-decoration: none;
            color: var(--text-main);
            font-size: .92rem;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 14px;
            border: 1px solid var(--header-btn-border);
            background: var(--header-btn-bg);
            transition: .18s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            letter-spacing: -0.01em;
            cursor: pointer;
        }

        .header-link:hover {
            background: var(--header-btn-hover);
            color: var(--text-main);
        }

        .logout-btn {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--header-btn-border);
            border-radius: 14px;
            padding: 10px 14px;
            font-size: .9rem;
            font-weight: 600;
            min-height: 44px;
            transition: .18s ease;
            font-family: 'Inter', Arial, sans-serif;
            letter-spacing: -0.01em;
        }

        .logout-btn:hover {
            background: var(--header-btn-hover);
            border-color: var(--header-btn-border);
        }

        .theme-toggle-btn {
            width: 52px;
            padding: 0;
        }

        .theme-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .theme-icon svg {
            width: 20px;
            height: 20px;
        }

        .theme-icon-sun,
        .theme-icon-moon {
            display: none;
        }

        .theme-dark .theme-icon-sun {
            display: inline-flex;
        }

        .theme-light .theme-icon-moon {
            display: inline-flex;
        }

        .page-body {
            padding-bottom: 100px;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: #111;
            border: none;
            border-radius: 18px;
            box-shadow: none;
            transition: .18s ease;
            font-family: 'Inter', Arial, sans-serif;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .btn-primary-custom:hover {
            background: var(--primary-hover);
            color: #111;
            transform: translateY(-1px);
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }

        .card-dark {
            background: var(--card-gradient);
            border: 1px solid var(--border-soft);
            border-radius: 24px;
            box-shadow: var(--shadow-main);
        }

        .text-income { color: var(--income); }
        .text-expense { color: var(--expense); }
        .text-balance-negative { color: var(--expense); }
        .text-balance-positive { color: var(--text-main); }

        .fab-add {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 24px rgba(0,0,0,.28);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            z-index: 60;
            transition: .18s ease;
        }

        .fab-add:hover {
            transform: translateX(-50%) translateY(-2px);
            color: #fff;
            background: var(--primary-hover);
        }

        .fab-add svg {
            width: 30px;
            height: 30px;
        }

        input, select, textarea {
            background: var(--input-bg) !important;
            color: var(--input-text) !important;
            border: 1px solid var(--border-soft) !important;
            border-radius: 18px !important;
            min-height: 52px;
            box-shadow: none !important;
            font-family: 'Inter', Arial, sans-serif !important;
            font-weight: 400 !important;
        }

        textarea {
            min-height: 110px;
        }

        input::placeholder, textarea::placeholder {
            color: var(--placeholder) !important;
        }

        label {
            color: var(--text-main);
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        .month-title {
            color: var(--primary);
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            white-space: nowrap;
        }

        .install-sheet-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.52);
            display: none;
            align-items: end;
            justify-content: center;
            z-index: 9999;
        }

        .install-sheet-overlay.show {
            display: flex;
        }

        .install-sheet {
            width: 100%;
            max-width: 760px;
            background: var(--sheet-gradient);
            color: var(--text-main);
            padding: 22px;
            border-top-left-radius: 28px;
            border-top-right-radius: 28px;
            box-shadow: 0 -18px 36px rgba(0,0,0,.34);
        }

        .sheet-handle,
        .install-sheet-handle {
            width: 54px;
            height: 6px;
            border-radius: 999px;
            background: var(--sheet-handle-bg);
            margin: 0 auto 18px auto;
        }

        .install-sheet-title {
            margin: 0 0 8px 0;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .install-sheet-text {
            margin: 0 0 18px 0;
            color: var(--text-soft);
            font-size: .96rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .install-sheet-actions {
            display: grid;
            gap: 10px;
        }

        .install-sheet-btn {
            min-height: 54px;
            border-radius: 18px;
            font-size: .98rem;
            font-weight: 600;
            border: none;
            letter-spacing: -0.01em;
        }

        .install-sheet-btn-primary {
            background: var(--primary);
            color: #111;
        }

        .install-sheet-btn-secondary {
            background: var(--install-secondary-bg);
            color: var(--install-secondary-text);
            border: 1px solid var(--install-secondary-border);
        }

        .install-sheet-small {
            font-size: .9rem;
            color: var(--text-soft);
            margin-top: 10px;
            line-height: 1.5;
        }

        @supports (padding-top: env(safe-area-inset-top)) {
            .app-header {
                padding-top: calc(14px + env(safe-area-inset-top));
            }
        }

        @media (max-width: 576px) {
            .app-header {
                padding-left: 14px;
                padding-right: 14px;
                padding-bottom: 12px;
            }

            .header-actions {
                gap: 6px;
            }

            .brand-name {
                font-size: 1.08rem;
            }

            .brand-sub {
                display: block;
                font-size: .68rem;
                margin-top: 2px;
                line-height: 1.1;
                white-space: nowrap;
            }

            .header-link {
                padding: 7px 10px;
                font-size: .82rem;
                min-height: 38px;
            }

            .logout-btn {
                padding: 7px 10px;
                font-size: .82rem;
                min-height: 38px;
            }

            .month-title {
                font-size: 1.7rem;
            }

            .fab-add {
                width: 66px;
                height: 66px;
                bottom: calc(18px + env(safe-area-inset-bottom, 0px));
            }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @auth
            <header class="app-header">
                <div class="app-header-inner">
                    <div class="brand-wrap">
                        <div class="brand-badge">
                            <img src="/logo-nav.png?v=4" alt="Logo Moneti">
                        </div>
                        <div>
                            <h1 class="brand-name">Moneti</h1>
                            <p class="brand-sub">Controle de despesas</p>
                        </div>
                    </div>

                    <div class="header-actions">
                        <button type="button" class="header-link theme-toggle-btn" id="themeToggleBtn" aria-label="Alternar tema">
                            <span class="theme-icon theme-icon-sun" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="4"/>
                                    <path d="M12 2v2"/>
                                    <path d="M12 20v2"/>
                                    <path d="m4.93 4.93 1.41 1.41"/>
                                    <path d="m17.66 17.66 1.41 1.41"/>
                                    <path d="M2 12h2"/>
                                    <path d="M20 12h2"/>
                                    <path d="m6.34 17.66-1.41 1.41"/>
                                    <path d="m19.07 4.93-1.41 1.41"/>
                                </svg>
                            </span>

                            <span class="theme-icon theme-icon-moon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                </svg>
                            </span>
                        </button>

                        <a href="{{ route('profile.edit') }}" class="header-link">Perfil</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="logout-btn" type="submit">Sair</button>
                        </form>
                    </div>
                </div>
            </header>
        @endauth

        <main class="page-body">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <div id="installSheetOverlay" class="install-sheet-overlay">
        <div class="install-sheet">
            <div class="install-sheet-handle"></div>
            <h3 class="install-sheet-title">Adicionar à tela inicial</h3>
            <p id="installSheetText" class="install-sheet-text">
                Instale o Moneti no seu celular para abrir como app.
            </p>

            <div class="install-sheet-actions">
                <button id="installSheetPrimaryBtn" class="install-sheet-btn install-sheet-btn-primary" type="button">
                    Instalar app
                </button>
                <button id="installSheetCloseBtn" class="install-sheet-btn install-sheet-btn-secondary" type="button">
                    Agora não
                </button>
            </div>

            <div id="installSheetSmall" class="install-sheet-small"></div>
        </div>
    </div>

    <script>
        function releasePageRender() {
            document.documentElement.classList.remove('app-loading');
            document.documentElement.classList.add('app-ready');
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            requestAnimationFrame(releasePageRender);
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                requestAnimationFrame(releasePageRender);
            });
        }

        @if(app()->environment('production'))
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', async () => {
                    try {
                        await navigator.serviceWorker.register('/sw.js');
                    } catch (error) {
                        console.error('Erro ao registrar Service Worker:', error);
                    }
                });
            }
        @else
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then((registrations) => {
                    registrations.forEach((registration) => registration.unregister());
                });

                if ('caches' in window) {
                    caches.keys().then((keys) => {
                        keys.forEach((key) => caches.delete(key));
                    });
                }
            }
        @endif

        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeColorMeta = document.getElementById('theme-color-meta');

        function isLightTheme() {
            return document.documentElement.classList.contains('theme-light');
        }

        function applyTheme(theme) {
            document.documentElement.classList.remove('theme-light', 'theme-dark');
            document.documentElement.classList.add(theme === 'light' ? 'theme-light' : 'theme-dark');

            localStorage.setItem('moneti_theme', theme);

            if (themeColorMeta) {
                themeColorMeta.setAttribute('content', theme === 'light' ? '#f7f9fc' : '#1f1f1f');
            }

            if (themeToggleBtn) {
                themeToggleBtn.setAttribute(
                    'aria-label',
                    theme === 'light' ? 'Ativar modo escuro' : 'Ativar modo claro'
                );
            }
        }

        themeToggleBtn?.addEventListener('click', function () {
            applyTheme(isLightTheme() ? 'dark' : 'light');
        });

        applyTheme(isLightTheme() ? 'light' : 'dark');

        let deferredInstallPrompt = null;
        const installSheetOverlay = document.getElementById('installSheetOverlay');
        const installSheetPrimaryBtn = document.getElementById('installSheetPrimaryBtn');
        const installSheetCloseBtn = document.getElementById('installSheetCloseBtn');
        const installSheetText = document.getElementById('installSheetText');
        const installSheetSmall = document.getElementById('installSheetSmall');

        const isSmallScreen = window.matchMedia('(max-width: 768px)').matches;
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

        function isIos() {
            return /iphone|ipad|ipod/i.test(window.navigator.userAgent);
        }

        function isSafari() {
            return /^((?!chrome|android).)*safari/i.test(window.navigator.userAgent);
        }

        function canShowInstallPromptAgain() {
            const dismissedAt = localStorage.getItem('moneti_install_dismissed_at');
            if (!dismissedAt) return true;

            const dismissedTime = parseInt(dismissedAt, 10);
            const now = Date.now();
            const oneDay = 24 * 60 * 60 * 1000;

            return (now - dismissedTime) > oneDay;
        }

        function openInstallSheet() {
            installSheetOverlay.classList.add('show');
        }

        function closeInstallSheet(saveDismiss = true) {
            installSheetOverlay.classList.remove('show');

            if (saveDismiss) {
                localStorage.setItem('moneti_install_dismissed_at', Date.now().toString());
            }
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredInstallPrompt = e;

            if (isSmallScreen && !isStandalone && canShowInstallPromptAgain()) {
                installSheetText.textContent = 'Instale o Moneti no seu celular para abrir como app.';
                installSheetSmall.textContent = '';
                installSheetPrimaryBtn.textContent = 'Instalar app';
                openInstallSheet();
            }
        });

        installSheetPrimaryBtn?.addEventListener('click', async () => {
            if (deferredInstallPrompt) {
                deferredInstallPrompt.prompt();
                const { outcome } = await deferredInstallPrompt.userChoice;
                deferredInstallPrompt = null;

                if (outcome === 'accepted') {
                    closeInstallSheet(false);
                } else {
                    closeInstallSheet(true);
                }
                return;
            }

            if (isIos() && isSafari() && isSmallScreen && !isStandalone) {
                installSheetText.textContent = 'No iPhone, toque em Compartilhar e depois em “Adicionar à Tela de Início”.';
                installSheetSmall.textContent = 'O Safari não mostra o botão de instalação automático como no Android.';
            }
        });

        installSheetCloseBtn?.addEventListener('click', () => {
            closeInstallSheet(true);
        });

        if (isIos() && isSafari() && isSmallScreen && !isStandalone && canShowInstallPromptAgain()) {
            installSheetText.textContent = 'Adicione o Moneti à tela inicial do iPhone.';
            installSheetSmall.textContent = 'Toque em Compartilhar e depois em “Adicionar à Tela de Início”.';
            installSheetPrimaryBtn.textContent = 'Entendi';
            openInstallSheet();
        }

        window.addEventListener('appinstalled', () => {
            closeInstallSheet(false);
            deferredInstallPrompt = null;
        });
    </script>
</body>
</html>