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
                <h1 class="form-page-title">Novo lançamento</h1>
                <p class="form-page-subtitle">Cadastre uma receita ou despesa</p>
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
                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf

                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                    <input type="hidden" name="tab" value="{{ request('tab', 'all') }}">

                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" id="type" class="form-select">
                            <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Despesa</option>
                            <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>Receita</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valor</label>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="0,00" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description') }}" placeholder="Ex.: Conta de luz" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data de vencimento</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Repetição</label>
                        <select name="repeat_type" id="repeat_type" class="form-select">
                            <option value="none" {{ old('repeat_type') === 'none' ? 'selected' : '' }}>Não repetir</option>
                            <option value="always" {{ old('repeat_type') === 'always' ? 'selected' : '' }}>Sempre</option>
                            <option value="installment" {{ old('repeat_type') === 'installment' ? 'selected' : '' }}>Parcelado</option>
                        </select>
                    </div>

                    <div class="mb-4" id="installments_wrap" style="{{ old('repeat_type') === 'installment' ? '' : 'display:none;' }}">
                        <label class="form-label">Número de parcelas</label>
                        <input type="number" min="2" max="360" name="installments_total" class="form-control" value="{{ old('installments_total') }}" placeholder="Ex.: 12">
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3 form-submit-btn">
                        Salvar lançamento
                    </button>
                </form>
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
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const repeatType = document.getElementById('repeat_type');
        const installmentsWrap = document.getElementById('installments_wrap');

        function toggleInstallments() {
            if (!repeatType || !installmentsWrap) return;
            installmentsWrap.style.display = repeatType.value === 'installment' ? '' : 'none';
        }

        repeatType?.addEventListener('change', toggleInstallments);
        toggleInstallments();
    });
</script>
@endsection