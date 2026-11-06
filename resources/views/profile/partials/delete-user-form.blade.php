<section>
    <p class="section-helper-text mb-4">
        Ao excluir sua conta, todos os dados associados serão removidos permanentemente. Essa ação não poderá ser desfeita.
    </p>

    <button type="button" class="delete-account-btn" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Excluir minha conta
    </button>
</section>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-bottom">
        <div class="modal-content profile-delete-sheet border-0">
            <div class="modal-body p-4">
                <div class="sheet-handle"></div>

                <h3 class="delete-modal-title mb-2">Excluir conta</h3>
                <p class="delete-modal-text mb-4">
                    Tem certeza que deseja excluir sua conta? Digite sua senha para confirmar.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" class="d-grid gap-3">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password" class="form-label">Senha</label>
                        <input id="password" name="password" type="password" class="form-control" placeholder="Digite sua senha" autocomplete="current-password">
                        @error('password', 'userDeletion')
                            <div class="form-error-text mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 pt-2">
                        <button type="submit" class="delete-confirm-btn">
                            Confirmar exclusão
                        </button>

                        <button type="button" class="delete-cancel-btn" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

    .delete-account-btn {
        min-height: 50px;
        border-radius: 16px;
        border: 1px solid rgba(220, 76, 63, .28);
        background: rgba(220, 76, 63, .08);
        color: var(--expense);
        padding: 0 18px;
        font-size: .96rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        transition: .18s ease;
    }

    .delete-account-btn:hover {
        background: rgba(220, 76, 63, .14);
    }

    .profile-delete-sheet {
        background: var(--sheet-gradient);
        color: var(--text-main);
        border-top-left-radius: 28px !important;
        border-top-right-radius: 28px !important;
        box-shadow: 0 -20px 40px rgba(0,0,0,.38);
    }

    .delete-modal-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--text-main);
    }

    .delete-modal-text {
        color: var(--text-soft);
        font-size: .96rem;
        line-height: 1.5;
    }

    .delete-confirm-btn {
        min-height: 54px;
        border-radius: 18px;
        border: none;
        background: var(--expense);
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .delete-confirm-btn:hover {
        filter: brightness(.96);
    }

    .delete-cancel-btn {
        min-height: 52px;
        border-radius: 18px;
        border: 1px solid var(--border-soft);
        background: rgba(255,255,255,.04);
        color: var(--text-main);
        font-size: .96rem;
        font-weight: 600;
        letter-spacing: -0.01em;
    }

    .delete-cancel-btn:hover {
        background: rgba(255,255,255,.08);
    }

    .modal-dialog-bottom {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0;
        width: 100%;
        max-width: 100%;
    }

    .modal-dialog-bottom .modal-content {
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .theme-light .delete-cancel-btn {
        background: rgba(15,23,42,.03);
    }
</style>