<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Moneti</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .header {
            margin-bottom: 20px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 12px;
            color: #666;
        }

        .summary {
            margin: 20px 0;
            width: 100%;
        }

        .summary td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .summary strong {
            display: block;
            margin-bottom: 4px;
        }

        .balance {
            font-size: 18px;
            font-weight: bold;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.report th,
        table.report td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        table.report th {
            background: #f2f2f2;
        }

        .income {
            color: #198754;
            font-weight: bold;
        }

        .expense {
            color: #dc3545;
            font-weight: bold;
        }

        .small {
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Relatório Moneti</div>
        <div class="subtitle">Período: {{ $monthName }}</div>
        <div class="subtitle">
            Aba:
            @if($tab === 'income')
                Receitas
            @elseif($tab === 'expense')
                Despesas
            @else
                Todos
            @endif
        </div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <strong>Receitas</strong>
                R$ {{ number_format($incomeTotal, 2, ',', '.') }}
            </td>
            <td>
                <strong>Despesas</strong>
                R$ {{ number_format($expenseTotal, 2, ',', '.') }}
            </td>
            <td>
                <strong>Saldo</strong>
                <span class="balance">R$ {{ number_format($balance, 2, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th>Repetição</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td class="{{ $transaction->type === 'income' ? 'income' : 'expense' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                    </td>
                    <td>{{ $transaction->due_date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->status_short_text }}</td>
                    <td>
                        @if($transaction->repeat_type === 'always')
                            Recorrente
                        @elseif($transaction->repeat_type === 'installment')
                            {{ $transaction->installment_number }}/{{ $transaction->installments_total }}
                        @else
                            Não repetir
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhum lançamento encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>