<x-guest-layout>
    <div class="auth-screen-wrap">
        <div class="auth-screen-card">
            <div class="auth-brand-area">
                <div class="auth-brand-badge">
                    <img src="/logo-login.png?v=3" alt="Logo Moneti">
                </div>
                <h1 class="auth-brand-title">Criar conta</h1>
                <p class="auth-brand-subtitle">Comece seu controle financeiro no Moneti</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="auth-form-group">
                    <label for="name" class="auth-label">Nome</label>
                    <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
                </div>

                <div class="auth-form-group">
                    <label for="email" class="auth-label">E-mail</label>
                    <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-label">Senha</label>
                    <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <div class="auth-form-group">
                    <label for="password_confirmation" class="auth-label">Confirmar senha</label>
                    <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>

                <div class="auth-links-row auth-links-row-register">
                    <a class="auth-secondary-link" href="{{ route('login') }}">
                        Já tenho conta
                    </a>
                </div>

                <button type="submit" class="auth-submit-btn">
                    Criar conta
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
            width: 80px;
            height: 80px;
            margin: 0 auto 14px auto;
            border-radius: 10px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .auth-brand-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .auth-brand-title {
            margin: 0;
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .auth-brand-subtitle {
            margin: 8px 0 0 0;
            color: #b9b9b9;
            font-size: .96rem;
            font-weight: 400;
            letter-spacing: 0;
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
            font-size: .98rem;
            font-weight: 600;
            letter-spacing: -0.01em;
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
            font-weight: 400;
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
            letter-spacing: -0.01em;
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
            font-size: 1.08rem;
            font-weight: 600;
            letter-spacing: -0.01em;
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
                font-size: .9rem;
            }

            .auth-input {
                min-height: 54px;
                font-size: .97rem;
            }

            .auth-submit-btn {
                min-height: 56px;
                font-size: 1rem;
            }
        }
    </style>
</x-guest-layout>