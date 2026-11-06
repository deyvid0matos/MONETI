<x-guest-layout>
    <div class="auth-screen-wrap">
        <div class="auth-screen-card">
            <div class="auth-brand-area">
                <div class="auth-brand-badge">
                    <svg data-lucide="key-round"></svg>
                </div>
                <h1 class="auth-brand-title">Recuperar senha</h1>
                <p class="auth-brand-subtitle">
                    Informe seu e-mail para receber o link de redefinição.
                </p>
            </div>

            @if (session('status'))
                <div class="auth-status-box">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf

                <div class="auth-form-group">
                    <label for="email" class="auth-label">E-mail</label>
                    <input
                        id="email"
                        class="auth-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <div class="auth-links-row auth-links-row-register">
                    <a class="auth-secondary-link" href="{{ route('login') }}">
                        Voltar para login
                    </a>
                </div>

                <button type="submit" class="auth-submit-btn">
                    Enviar link
                </button>
            </form>
        </div>
    </div>

    <style>
        .auth-screen-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
            background: linear-gradient(180deg, #1c1c1c 0%, #202020 100%);
        }

        .auth-screen-card {
            width: 100%;
            max-width: 430px;
            background: linear-gradient(180deg, #2f2f2f 0%, #282828 100%);
            border: 1px solid rgba(255,255,255,.05);
            border-radius: 30px;
            padding: 28px 24px;
            box-shadow: 0 16px 34px rgba(0,0,0,.28);
        }

        .auth-brand-area {
            text-align: center;
            margin-bottom: 26px;
        }

        .auth-brand-badge {
            width: 60px;
            height: 60px;
            margin: 0 auto 14px auto;
            border-radius: 18px;
            background: linear-gradient(135deg, #19c6df, #0b8fa2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .auth-brand-badge svg {
            width: 28px;
            height: 28px;
            stroke-width: 2.2;
        }

        .auth-brand-title {
            margin: 0;
            color: #fff;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            line-height: 1;
        }

        .auth-brand-subtitle {
            margin: 8px 0 0 0;
            color: var(--text-soft);
            font-size: .98rem;
            font-weight: 500;
            letter-spacing: -0.02em;
            line-height: 1.5;
        }

        .auth-status-box {
            background: rgba(51,209,107,.10);
            color: #9df0b8;
            border: 1px solid rgba(51,209,107,.18);
            border-radius: 18px;
            padding: 14px 16px;
            font-size: .94rem;
            font-weight: 500;
            margin-bottom: 18px;
            line-height: 1.5;
        }

        .auth-form {
            margin-top: 8px;
        }

        .auth-form-group {
            margin-bottom: 18px;
        }

        .auth-label {
            display: block;
            margin-bottom: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .auth-input {
            width: 100%;
            min-height: 56px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,.10);
            background: #555;
            color: #fff;
            padding: 0 16px;
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            box-shadow: none;
        }

        .auth-input:focus {
            border-color: rgba(18,203,230,.45);
        }

        .auth-links-row {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .auth-links-row-register {
            margin-top: 6px;
        }

        .auth-secondary-link {
            color: #d9d9d9;
            text-decoration: none;
            font-size: .95rem;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .auth-secondary-link:hover {
            color: #fff;
        }

        .auth-submit-btn {
            width: 100%;
            min-height: 58px;
            border: none;
            border-radius: 18px;
            background: #20bfd8;
            color: #111;
            font-size: 1.12rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            transition: .18s ease;
        }

        .auth-submit-btn:hover {
            background: #17b0c7;
        }

        @media (max-width: 576px) {
            .auth-screen-wrap {
                padding: 16px;
            }

            .auth-screen-card {
                padding: 24px 18px;
                border-radius: 26px;
            }

            .auth-brand-title {
                font-size: 1.7rem;
            }

            .auth-brand-subtitle {
                font-size: .92rem;
            }

            .auth-input {
                min-height: 54px;
                font-size: .98rem;
            }

            .auth-submit-btn {
                min-height: 56px;
                font-size: 1.04rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</x-guest-layout>