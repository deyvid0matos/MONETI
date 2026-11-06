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
            --body-gradient: linear-gradient(180deg, #1c1c1c 0%, #202020 100%);
            --text-main: #ffffff;
            --text-soft: #b9b9b9;
            --primary: #12cbe6;
        }

        .theme-light {
            --body-gradient: linear-gradient(180deg, #f7f9fc 0%, #eef3f8 100%);
            --text-main: #1b2430;
            --text-soft: #667085;
            --primary: #0ea5c6;
        }

        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background: var(--body-gradient);
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: var(--body-gradient);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
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
    </style>
</head>
<body>
    {{ $slot }}

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

        const themeColorMeta = document.getElementById('theme-color-meta');

        if (themeColorMeta) {
            themeColorMeta.setAttribute(
                'content',
                document.documentElement.classList.contains('theme-light') ? '#f7f9fc' : '#1f1f1f'
            );
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
    </script>
</body>
</html>