@extends('layouts.app')

@section('content')
<div class="transaction-page-wrap">
    <div class="p-3 p-md-4 pb-5">
        <div class="form-topbar mb-4">
            <a href="{{ route('dashboard', ['month' => request('month', now()->month), 'year' => request('year', now()->year), 'tab' => request('tab', 'all')]) }}" class="back-link-btn" aria-label="Voltar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </a>

            <div>
                <h1 class="form-page-title">Editar lançamento</h1>
                <p class="form-page-subtitle">Atualize os dados deste lançamento</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-dark form-card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('transactions.update', $transaction) }}" id="editTransactionForm">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                    <input type="hidden" name="tab" value="{{ request('tab', 'all') }}">
                    <input type="hidden" name="update_scope" id="update_scope" value="single">

                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="expense" {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>Despesa</option>
                            <option value="income" {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>Receita</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valor</label>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount', $transaction->amount) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description', $transaction->description) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data de vencimento</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', optional($transaction->due_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Repetição</label>
                        <select name="repeat_type" id="repeat_type" class="form-select" required>
                            <option value="none" {{ old('repeat_type', $transaction->repeat_type) === 'none' ? 'selected' : '' }}>Não repetir</option>
                            <option value="always" {{ old('repeat_type', $transaction->repeat_type) === 'always' ? 'selected' : '' }}>Sempre</option>
                            <option value="installment" {{ old('repeat_type', $transaction->repeat_type) === 'installment' ? 'selected' : '' }}>Parcelado</option>
                        </select>
                        <div class="form-help-text mt-2">
                            Em lançamentos que já fazem parte de uma série, mudanças estruturais funcionam em “esta e futuras” ou “série inteira”.
                        </div>
                    </div>

                    <div class="mb-4" id="installments_wrap" style="{{ old('repeat_type', $transaction->repeat_type) === 'installment' ? '' : 'display:none;' }}">
                        <label class="form-label">Número de parcelas</label>
                        <input type="number" min="2" max="360" name="installments_total" class="form-control" value="{{ old('installments_total', $transaction->installments_total) }}" placeholder="Ex.: 12">
                        <div class="form-help-text mt-2">
                            Só é usado quando a repetição for “Parcelado”.
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary-custom w-100 py-3 form-submit-btn" data-bs-toggle="modal" data-bs-target="#updateScopeModal">
                        Atualizar lançamento
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateScopeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-bottom">
            <div class="modal-content transaction-sheet border-0">
                <div class="modal-body p-4">
                    <div class="sheet-handle"></div>

                    <h3 class="popup-title mb-2">Atualizar lançamento</h3>
                    <p class="popup-help-text mb-4">Escolha como deseja aplicar esta edição.</p>

                    @if($transaction->is_series)
                        <div class="d-grid gap-3">
                            <button type="button" class="btn btn-outline-light w-100 py-3 popup-main-btn update-scope-btn" data-scope="single">
                                Atualizar só esta
                            </button>

                            <button type="button" class="btn btn-outline-light w-100 py-3 popup-main-btn update-scope-btn" data-scope="future">
                                Atualizar esta e futuras
                            </button>

                            <button type="button" class="btn btn-primary-custom w-100 py-3 popup-main-btn update-scope-btn" data-scope="series">
                                Atualizar série inteira
                            </button>
                        </div>
                    @else
                        <div class="d-grid gap-3">
                            <button type="button" class="btn btn-primary-custom w-100 py-3 popup-main-btn update-scope-btn" data-scope="single">
                                Atualizar lançamento
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transaction-page-wrap {
        max-width: 720px;
        margin: 0 auto;
        min-height: 100vh;
        padding-bottom: 50px;
    }

    .form-topbar {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .back-link-btn {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border-soft);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: .18s ease;
    }

    .back-link-btn:hover {
        color: var(--primary);
        background: rgba(255,255,255,.08);
        transform: translateY(-1px);
    }

    .form-page-title {
        margin: 0;
        font-size: 1.7rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--text-main);
    }

    .form-page-subtitle {
        margin: 4px 0 0;
        color: var(--text-soft);
        font-size: .96rem;
    }

    .form-card {
        border-radius: 28px;
    }

    .form-submit-btn {
        font-size: 1.05rem;
        font-weight: 600;
        min-height: 56px;
        border-radius: 18px;
    }

    .form-help-text {
        color: var(--text-soft);
        font-size: .9rem;
        line-height: 1.45;
    }

    .transaction-sheet {
        background: var(--sheet-gradient);
        color: var(--text-main);
        border-top-left-radius: 28px !important;
        border-top-right-radius: 28px !important;
        box-shadow: 0 -20px 40px rgba(0,0,0,.38);
    }

    .sheet-handle {
        width: 54px;
        height: 6px;
        border-radius: 999px;
        background: var(--sheet-handle-bg);
        margin: 0 auto 18px auto;
    }

    .popup-title {
        font-size: 1.7rem;
        font-weight: 700;
        text-transform: uppercase;
        line-height: 1.08;
        letter-spacing: -0.03em;
    }

    .popup-help-text {
        color: var(--text-soft);
        font-size: .96rem;
        font-weight: 400;
        line-height: 1.5;
    }

    .popup-main-btn {
        font-size: 1.04rem;
        font-weight: 600;
        min-height: 56px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border-radius: 18px;
        white-space: nowrap;
        letter-spacing: -0.01em;
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

    .theme-light .back-link-btn {
        background: rgba(15,23,42,.03);
    }

    @media (max-width: 576px) {
        .form-page-title {
            font-size: 1.45rem;
        }

        .back-link-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
        }

        .back-link-btn svg {
            width: 22px;
            height: 22px;
        }

        .popup-title {
            font-size: 1.36rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const repeatType = document.getElementById('repeat_type');
        const installmentsWrap = document.getElementById('installments_wrap');
        const updateScopeInput = document.getElementById('update_scope');
        const editForm = document.getElementById('editTransactionForm');
        const scopeButtons = document.querySelectorAll('.update-scope-btn');

        function toggleInstallments() {
            if (!repeatType || !installmentsWrap) return;
            installmentsWrap.style.display = repeatType.value === 'installment' ? '' : 'none';
        }

        repeatType?.addEventListener('change', toggleInstallments);
        toggleInstallments();

        scopeButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const scope = this.getAttribute('data-scope') || 'single';

                if (updateScopeInput) {
                    updateScopeInput.value = scope;
                }

                if (editForm) {
                    editForm.submit();
                }
            });
        });
    });
</script>
@endsection