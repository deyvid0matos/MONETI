<section>
    <p class="section-helper-text mb-4">
        Use uma senha forte para manter sua conta protegida.
    </p>

    <form method="post" action="{{ route('password.update') }}" class="d-grid gap-3">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label">Senha atual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="form-error-text mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="form-label">Nova senha</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="form-error-text mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label">Confirmar nova senha</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="form-error-text mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800">
                Atualizar senha
            </button>

            @if (session('status') === 'password-updated')
                <span class="success-inline-text">Senha atualizada com sucesso.</span>
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

    .success-inline-text {
        color: var(--income);
        font-size: .92rem;
        font-weight: 600;
    }
</style>