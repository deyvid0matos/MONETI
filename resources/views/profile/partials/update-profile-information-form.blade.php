<section>
    <p class="section-helper-text mb-4">
        Atualize seu nome e e-mail da conta.
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="d-grid gap-3">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-label">Nome</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="form-error-text mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email" class="form-label">E-mail</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="form-error-text mt-2">{{ $message }}</div>
            @enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="verification-box">
                <p class="verification-text mb-2">
                    Seu endereço de e-mail ainda não foi verificado.
                </p>

                <button form="send-verification" class="verification-link-btn" type="submit">
                    Reenviar e-mail de verificação
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="success-inline-text mt-2">
                        Um novo link de verificação foi enviado para seu e-mail.
                    </p>
                @endif
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 flex-wrap pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800">
                Salvar alterações
            </button>

            @if (session('status') === 'profile-updated')
                <span class="success-inline-text">Salvo com sucesso.</span>
            @endif
        </div>
    </form>
</section>

<style>
    .section-helper-text {
        color: var(--text-soft);
        font-size: .95rem;
        line-height: 1.5;
        margin: 0;
    }

    .form-error-text {
        color: var(--expense);
        font-size: .88rem;
        font-weight: 500;
    }

    .verification-box {
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border-soft);
    }

    .verification-text {
        color: var(--text-soft) !important;
        font-size: .92rem;
        line-height: 1.5;
    }

    .verification-link-btn {
        background: transparent;
        border: none;
        padding: 0;
        color: var(--primary);
        font-size: .95rem;
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
    }

    .success-inline-text {
        color: var(--income);
        font-size: .92rem;
        font-weight: 600;
    }

    .theme-light .verification-box {
        background: rgba(15,23,42,.03);
    }
</style>