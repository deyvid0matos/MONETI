@extends('layouts.app')

@section('content')
<div class="app-mobile-page">
    <div class="p-3 p-md-4 pb-5">
        @if(session('success'))
            <div class="alert alert-success mb-3 rounded-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="month-nav-center mb-4">
            <a class="month-arrow-btn" href="{{ route('dashboard', ['month' => $prevMonth->month, 'year' => $prevMonth->year, 'tab' => $tab]) }}" aria-label="Mês anterior">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </a>

            <a class="month-arrow-btn month-today-btn" href="{{ route('dashboard', ['month' => now()->month, 'year' => now()->year, 'tab' => $tab]) }}" aria-label="Voltar para o mês atual" title="Mês atual">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M8 2v4"/>
                    <path d="M16 2v4"/>
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M3 10h18"/>
                    <path d="M12 14h.01"/>
                </svg>
            </a>

            <div class="month-title">{{ $monthName }}</div>

            <a class="month-arrow-btn" href="{{ route('dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year, 'tab' => $tab]) }}" aria-label="Próximo mês">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        </div>

        <div class="card card-dark summary-card mb-4">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="summary-title">Balanço do mês</div>

                <div class="{{ $balance < 0 ? 'text-balance-negative' : 'text-balance-positive' }} summary-balance">
                    R$ {{ number_format($balance, 2, ',', '.') }}
                </div>

                <div class="row mt-4 g-3">
                    <div class="col-6">
                        <div class="summary-mini-card income-mini">
                            <div class="summary-mini-top">
                                <span class="summary-mini-label text-income">Receitas</span>
                            </div>
                            <div class="summary-mini-value">
                                R$ {{ number_format($incomeTotal, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="summary-mini-card expense-mini">
                            <div class="summary-mini-top">
                                <span class="summary-mini-label text-expense">Despesas</span>
                            </div>
                            <div class="summary-mini-value">
                                R$ {{ number_format($expenseTotal, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="export-actions mb-4">
            <a
                href="{{ route('transactions.export.excel', ['month' => $month, 'year' => $year, 'tab' => $tab]) }}"
                class="export-btn"
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="4" y="2" width="16" height="20" rx="3"/>
                    <rect x="7" y="5" width="10" height="4" rx="1"/>
                    <path d="M8 13h.01"/>
                    <path d="M12 13h.01"/>
                    <path d="M16 13h.01"/>
                    <path d="M8 17h.01"/>
                    <path d="M12 17h.01"/>
                    <path d="M16 17h.01"/>
                </svg>
                <span>Excel</span>
            </a>

            <a
                href="{{ route('transactions.export.pdf', ['month' => $month, 'year' => $year, 'tab' => $tab]) }}"
                class="export-btn"
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7z"/>
                    <path d="M14 2v5h5"/>
                    <path d="M9 13h6"/>
                    <path d="M9 17h4"/>
                </svg>
                <span>PDF</span>
            </a>
        </div>

        <div id="dashboardSwipeArea" class="dashboard-swipe-area">
            <div class="tabs-mobile mb-4">
                <a class="tab-link {{ $tab === 'all' ? 'active' : '' }}"
                   href="{{ route('dashboard', ['month' => $month, 'year' => $year, 'tab' => 'all']) }}">
                    Todos
                </a>

                <a class="tab-link {{ $tab === 'income' ? 'active' : '' }}"
                   href="{{ route('dashboard', ['month' => $month, 'year' => $year, 'tab' => 'income']) }}">
                    Receitas
                </a>

                <a class="tab-link {{ $tab === 'expense' ? 'active' : '' }}"
                   href="{{ route('dashboard', ['month' => $month, 'year' => $year, 'tab' => 'expense']) }}">
                    Despesas
                </a>
            </div>

            <div class="transactions-fade">
                @forelse($transactions as $transaction)
                    @php
                        $statusKey = $transaction->status_key;
                        $statusText = $transaction->status_text;
                    @endphp

                    <div class="transaction-item-mobile" data-bs-toggle="modal" data-bs-target="#transactionModal{{ $transaction->id }}">
                        <div class="transaction-left">
                            <div class="transaction-status-icon">
                                @if(in_array($statusKey, ['expense_paid', 'income_received']))
                                    <span class="status-circle status-paid">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"/>
                                        </svg>
                                    </span>
                                @elseif(in_array($statusKey, ['expense_overdue', 'income_overdue']))
                                    <span class="status-circle status-overdue">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true">
                                            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                            <line x1="12" y1="9" x2="12" y2="13"/>
                                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="status-circle status-pending">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>

                            <div class="transaction-main-info">
                                <div class="transaction-title-mobile">
                                    {{ $transaction->description }}
                                </div>

                                <div class="transaction-date-mobile">
                                    {{ $transaction->due_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="transaction-right">
                            @if($transaction->type === 'income')
                                <div class="transaction-value-mobile text-income">
                                    + R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </div>
                            @else
                                <div class="transaction-value-mobile text-expense">
                                    - R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </div>
                            @endif

                            <div class="transaction-right-bottom">
                                @if($transaction->repeat_type === 'always')
                                    <span class="transaction-repeat-icon" title="Recorrente">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                            <path d="M17 2l4 4-4 4"/>
                                            <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                                            <path d="M7 22l-4-4 4-4"/>
                                            <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
                                        </svg>
                                    </span>
                                @elseif($transaction->repeat_type === 'installment')
                                    <span class="transaction-installment-badge">
                                        {{ $transaction->installment_number }}/{{ $transaction->installments_total }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="transactionModal{{ $transaction->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-bottom">
                            <div class="modal-content transaction-sheet border-0">
                                <div class="modal-body p-4">
                                    <div class="sheet-handle"></div>

                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="me-3">
                                            <div class="popup-title">
                                                {{ $transaction->description }}
                                            </div>
                                        </div>

                                        <div class="popup-actions">
                                            <a href="{{ route('transactions.edit', ['transaction' => $transaction, 'month' => $month, 'year' => $year, 'tab' => $tab]) }}"
                                               class="popup-icon-action"
                                               title="Editar">
                                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                    <path d="M12 20h9"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                                </svg>
                                            </a>

                                            <button
                                                type="button"
                                                class="popup-icon-action border-0 bg-transparent open-delete-scope-btn"
                                                title="Excluir"
                                                data-transaction-modal="transactionModal{{ $transaction->id }}"
                                                data-delete-modal="deleteScopeModal{{ $transaction->id }}"
                                            >
                                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6l-1 14H6L5 6"/>
                                                    <path d="M10 11v6"/>
                                                    <path d="M14 11v6"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="popup-info-block">
                                        <div class="popup-label">Valor</div>
                                        <div class="popup-value">
                                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="popup-info-block">
                                        <div class="popup-label">Data de vencimento</div>
                                        <div class="popup-value">
                                            {{ $transaction->due_date->format('d/m/Y') }}
                                        </div>
                                    </div>

                                    <div class="popup-info-block">
                                        @if(in_array($statusKey, ['expense_paid', 'income_received']))
                                            <div class="status-line status-paid-text">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                    <path d="M20 6 9 17l-5-5"/>
                                                </svg>
                                                <span>{{ $statusText }}</span>
                                            </div>
                                        @elseif(in_array($statusKey, ['expense_overdue', 'income_overdue']))
                                            <div class="status-line status-overdue-text">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                                    <line x1="12" y1="9" x2="12" y2="13"/>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                                </svg>
                                                <span>{{ $statusText }}</span>
                                            </div>
                                        @else
                                            <div class="status-line status-pending-text">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <polyline points="12 6 12 12 16 14"/>
                                                </svg>
                                                <span>{{ $statusText }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!$transaction->is_paid)
                                        <form method="POST" action="{{ route('transactions.paid', $transaction) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="month" value="{{ $month }}">
                                            <input type="hidden" name="year" value="{{ $year }}">
                                            <input type="hidden" name="tab" value="{{ $tab }}">
                                            <button type="submit" class="btn btn-primary-custom w-100 py-3 popup-main-btn">
                                                <span class="btn-inner-icon">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                        <path d="M20 6 9 17l-5-5"/>
                                                    </svg>
                                                </span>
                                                <span>{{ $transaction->type === 'income' ? 'Marcar como recebida' : 'Marcar como paga' }}</span>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('transactions.unpaid', $transaction) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="month" value="{{ $month }}">
                                            <input type="hidden" name="year" value="{{ $year }}">
                                            <input type="hidden" name="tab" value="{{ $tab }}">
                                            <button type="submit" class="btn btn-outline-light w-100 py-3 popup-main-btn popup-secondary-btn">
                                                <span class="btn-inner-icon">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                        <polyline points="1 4 1 10 7 10"/>
                                                        <path d="M3.51 15a9 9 0 1 0 .49-5"/>
                                                    </svg>
                                                </span>
                                                <span>Marcar como pendente</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteScopeModal{{ $transaction->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-bottom">
                            <div class="modal-content transaction-sheet border-0">
                                <div class="modal-body p-4">
                                    <div class="sheet-handle"></div>

                                    <h3 class="popup-title mb-2">Excluir lançamento</h3>
                                    <p class="popup-help-text mb-4">
                                        Escolha como deseja excluir este lançamento.
                                    </p>

                                    @if($transaction->is_series)
                                        <div class="d-grid gap-3">
                                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="delete_scope" value="single">
                                                <input type="hidden" name="month" value="{{ $month }}">
                                                <input type="hidden" name="year" value="{{ $year }}">
                                                <input type="hidden" name="tab" value="{{ $tab }}">
                                                <button type="submit" class="btn btn-outline-light w-100 py-3 popup-main-btn">
                                                    Excluir só esta
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="delete_scope" value="future">
                                                <input type="hidden" name="month" value="{{ $month }}">
                                                <input type="hidden" name="year" value="{{ $year }}">
                                                <input type="hidden" name="tab" value="{{ $tab }}">
                                                <button type="submit" class="btn btn-outline-light w-100 py-3 popup-main-btn">
                                                    Excluir esta e futuras
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="delete_scope" value="series">
                                                <input type="hidden" name="month" value="{{ $month }}">
                                                <input type="hidden" name="year" value="{{ $year }}">
                                                <input type="hidden" name="tab" value="{{ $tab }}">
                                                <button type="submit" class="btn btn-danger w-100 py-3 popup-main-btn">
                                                    Excluir série inteira
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="delete_scope" value="single">
                                            <input type="hidden" name="month" value="{{ $month }}">
                                            <input type="hidden" name="year" value="{{ $year }}">
                                            <input type="hidden" name="tab" value="{{ $tab }}">
                                            <button type="submit" class="btn btn-danger w-100 py-3 popup-main-btn">
                                                Excluir lançamento
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-mobile">
                        <div class="empty-state-icon">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 8a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v6.5A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5z"/>
                            </svg>
                        </div>
                        <div class="empty-state-title">Nenhum lançamento neste mês</div>
                        <div class="empty-state-text">Adicione sua primeira receita ou despesa para começar.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <a href="{{ route('transactions.create', ['month' => $month, 'year' => $year, 'tab' => $tab]) }}" class="fab-add" aria-label="Novo lançamento">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
    </a>
</div>

<style>
    .app-mobile-page {
        max-width: 720px;
        margin: 0 auto;
        min-height: 100vh;
        padding-bottom: 96px;
    }

    .dashboard-swipe-area {
        touch-action: pan-y;
    }

    .month-nav-center {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .month-arrow-btn {
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
        transition: .18s ease;
        flex-shrink: 0;
    }

    .month-arrow-btn:hover {
        transform: translateY(-1px);
        color: var(--primary);
        background: rgba(255,255,255,.07);
    }

    .month-today-btn {
        width: 44px;
        height: 44px;
        border-radius: 13px;
    }

    .summary-card {
        border-radius: 28px;
    }

    .summary-title {
        font-size: 1.2rem;
        color: var(--text-soft);
        margin-bottom: 8px;
        font-weight: 500;
        letter-spacing: -0.01em;
    }

    .summary-balance {
        font-size: 2.55rem;
        font-weight: 700;
        line-height: 1.08;
        letter-spacing: -0.03em;
        white-space: nowrap;
    }

    .summary-mini-card {
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border-soft);
        border-radius: 18px;
        padding: 14px;
        height: 100%;
    }

    .summary-mini-top {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .summary-mini-label {
        font-size: .94rem;
        font-weight: 600;
        letter-spacing: -0.01em;
    }

    .summary-mini-value {
        font-size: 1.38rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--text-main);
        white-space: nowrap;
    }

    .export-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .export-btn {
        min-height: 46px;
        padding: 0 16px;
        border-radius: 14px;
        border: 1px solid var(--border-soft);
        background: rgba(255,255,255,.04);
        color: var(--text-main);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: .96rem;
        font-weight: 600;
        transition: .18s ease;
        letter-spacing: -0.01em;
    }

    .export-btn:hover {
        background: rgba(255,255,255,.08);
        color: var(--text-main);
    }

    .tabs-mobile {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        font-size: 1rem;
        font-weight: 600;
        margin-top: 6px;
    }

    .tab-link {
        color: var(--text-soft);
        text-decoration: none;
        padding-bottom: 10px;
        border-bottom: 3px solid transparent;
        transition: color .22s ease, border-color .22s ease, transform .18s ease, opacity .22s ease;
        font-weight: 600;
        letter-spacing: -0.01em;
        opacity: .82;
    }

    .tab-link:hover {
        color: var(--text-main);
        opacity: 1;
        transform: translateY(-1px);
    }

    .tab-link.active {
        color: var(--text-main);
        border-color: var(--primary);
        opacity: 1;
    }

    .transactions-fade {
        animation: transactionsFadeIn .22s ease;
    }

    @keyframes transactionsFadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .transaction-item-mobile {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding: 18px 2px;
        border-bottom: 1px solid var(--border-soft);
        cursor: pointer;
        transition: .18s ease;
    }

    .transaction-item-mobile:hover {
        background: rgba(255,255,255,.02);
    }

    .transaction-left {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .transaction-status-icon {
        flex-shrink: 0;
    }

    .status-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.05);
    }

    .status-paid {
        color: var(--income);
        border: 2px solid rgba(51,209,107,.95);
    }

    .status-pending {
        color: var(--warning);
        border: 2px solid rgba(255,171,77,.95);
    }

    .status-overdue {
        color: var(--expense);
        border: 2px solid rgba(255,90,82,.95);
    }

    .transaction-main-info {
        flex: 1;
        min-width: 0;
        padding-top: 2px;
    }

    .transaction-title-mobile {
        font-size: 1.26rem;
        font-weight: 700;
        text-transform: uppercase;
        line-height: 1.14;
        letter-spacing: -0.02em;
        word-break: break-word;
    }

    .transaction-date-mobile {
        margin-top: 6px;
        font-size: .95rem;
        color: var(--text-soft);
        font-weight: 400;
        letter-spacing: 0;
    }

    .transaction-right {
        flex-shrink: 0;
        width: 132px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: flex-start;
        text-align: right;
        padding-top: 4px;
    }

    .transaction-value-mobile {
        font-size: 1.14rem;
        font-weight: 700;
        line-height: 1.1;
        width: 100%;
        text-align: right;
        letter-spacing: -0.02em;
        white-space: nowrap;
        word-break: normal;
        overflow-wrap: normal;
    }

    .transaction-right-bottom {
        width: 100%;
        margin-top: 8px;
        min-height: 22px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        text-align: right;
    }

    .transaction-repeat-icon {
        color: var(--text-main);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .transaction-installment-badge {
        font-size: 1rem;
        color: var(--text-main);
        line-height: 1;
        font-weight: 700;
        white-space: nowrap;
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

    .popup-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .popup-icon-action {
        color: var(--text-main);
        text-decoration: none;
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: rgba(255,255,255,.05);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .18s ease;
    }

    .popup-icon-action:hover {
        background: rgba(255,255,255,.09);
        color: var(--text-main);
    }

    .popup-info-block {
        margin-bottom: 22px;
    }

    .popup-label {
        font-size: .95rem;
        color: var(--text-soft);
        margin-bottom: 4px;
        font-weight: 400;
    }

    .popup-value {
        font-size: 1.48rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        white-space: nowrap;
    }

    .status-line {
        font-size: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.01em;
    }

    .status-paid-text { color: var(--income); }
    .status-pending-text { color: var(--warning); }
    .status-overdue-text { color: #ff7b74; }

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

    .popup-secondary-btn {
        border-radius: 18px;
    }

    .btn-inner-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-mobile {
        padding: 46px 20px;
        text-align: center;
        color: var(--text-soft);
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: rgba(255,255,255,.05);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .empty-state-icon svg {
        color: var(--primary);
    }

    .empty-state-title {
        color: var(--text-main);
        font-size: 1.06rem;
        font-weight: 600;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }

    .empty-state-text {
        font-size: .95rem;
        font-weight: 400;
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

    .theme-light .summary-mini-card,
    .theme-light .export-btn,
    .theme-light .month-arrow-btn,
    .theme-light .empty-state-icon,
    .theme-light .popup-icon-action {
        background: rgba(15,23,42,.03);
    }

    .theme-light .transaction-item-mobile:hover {
        background: rgba(15,23,42,.025);
    }

    .theme-light .status-circle {
        background: rgba(15,23,42,.03);
    }

    @media (max-width: 576px) {
        .summary-title {
            font-size: 1.02rem;
        }

        .summary-balance {
            font-size: 2.1rem;
        }

        .summary-mini-value {
            font-size: 1.14rem;
        }

        .month-nav-center {
            gap: 8px;
        }

        .month-arrow-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
        }

        .month-arrow-btn svg {
            width: 22px;
            height: 22px;
        }

        .month-today-btn {
            width: 38px;
            height: 38px;
        }

        .month-today-btn svg {
            width: 20px;
            height: 20px;
        }

        .tabs-mobile {
            font-size: .96rem;
        }

        .transaction-title-mobile {
            font-size: 1.1rem;
        }

        .transaction-date-mobile {
            font-size: .9rem;
        }

        .transaction-value-mobile {
            font-size: 1rem;
        }

        .transaction-right {
            width: 120px;
        }

        .transaction-installment-badge {
            font-size: .9rem;
        }

        .popup-title {
            font-size: 1.36rem;
        }

        .popup-value {
            font-size: 1.22rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swipeArea = document.getElementById('dashboardSwipeArea');

        if (swipeArea) {
            let startX = 0;
            let startY = 0;
            let endX = 0;
            let endY = 0;

            const currentTab = @json($tab);
            const month = @json($month);
            const year = @json($year);

            const tabsOrder = ['all', 'income', 'expense'];

            function goToTab(newTab) {
                const url = new URL(@json(route('dashboard')), window.location.origin);
                url.searchParams.set('month', month);
                url.searchParams.set('year', year);
                url.searchParams.set('tab', newTab);
                window.location.href = url.toString();
            }

            function handleSwipe() {
                const diffX = endX - startX;
                const diffY = endY - startY;
                const minSwipeDistance = 60;

                if (Math.abs(diffY) > Math.abs(diffX)) return;
                if (Math.abs(diffX) < minSwipeDistance) return;

                const currentIndex = tabsOrder.indexOf(currentTab);
                if (currentIndex === -1) return;

                if (diffX < 0) {
                    const nextIndex = currentIndex + 1;
                    if (tabsOrder[nextIndex]) {
                        goToTab(tabsOrder[nextIndex]);
                    }
                }

                if (diffX > 0) {
                    const prevIndex = currentIndex - 1;
                    if (tabsOrder[prevIndex]) {
                        goToTab(tabsOrder[prevIndex]);
                    }
                }
            }

            swipeArea.addEventListener('touchstart', function (e) {
                if (!e.touches || !e.touches.length) return;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });

            swipeArea.addEventListener('touchend', function (e) {
                if (!e.changedTouches || !e.changedTouches.length) return;
                endX = e.changedTouches[0].clientX;
                endY = e.changedTouches[0].clientY;
                handleSwipe();
            }, { passive: true });
        }

        const deleteButtons = document.querySelectorAll('.open-delete-scope-btn');

        deleteButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const transactionModalId = this.getAttribute('data-transaction-modal');
                const deleteModalId = this.getAttribute('data-delete-modal');

                const transactionModalEl = document.getElementById(transactionModalId);
                const deleteModalEl = document.getElementById(deleteModalId);

                if (!transactionModalEl || !deleteModalEl || typeof bootstrap === 'undefined') return;

                const transactionModal = bootstrap.Modal.getInstance(transactionModalEl) || new bootstrap.Modal(transactionModalEl);
                transactionModal.hide();

                setTimeout(() => {
                    const deleteModal = new bootstrap.Modal(deleteModalEl);
                    deleteModal.show();
                }, 250);
            });
        });
    });
</script>
@endsection