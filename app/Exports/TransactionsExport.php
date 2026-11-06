<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected int $userId;
    protected int $month;
    protected int $year;
    protected string $tab;

    public function __construct(int $userId, int $month, int $year, string $tab = 'all')
    {
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;
        $this->tab = $tab;
    }

    public function collection(): Collection
    {
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $end = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        $query = Transaction::query()
            ->where('user_id', $this->userId)
            ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('due_date')
            ->orderByDesc('id');

        if ($this->tab === 'income') {
            $query->where('type', 'income');
        }

        if ($this->tab === 'expense') {
            $query->where('type', 'expense');
        }

        return $query->get()->map(function ($transaction) {
            return [
                'tipo' => $transaction->type === 'income' ? 'Receita' : 'Despesa',
                'descricao' => $transaction->description,
                'valor' => number_format((float) $transaction->amount, 2, ',', '.'),
                'data_vencimento' => $transaction->due_date->format('d/m/Y'),
                'status' => $transaction->status_short_text,
                'repeticao' => match ($transaction->repeat_type) {
                    'always' => 'Recorrente',
                    'installment' => $transaction->installment_number . '/' . $transaction->installments_total,
                    default => 'Não repetir',
                },
                'pago_recebido_em' => $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i') : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Descrição',
            'Valor',
            'Data de vencimento',
            'Status',
            'Repetição',
            'Pago/Recebido em',
        ];
    }
}