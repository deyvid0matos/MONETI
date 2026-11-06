<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    protected function redirectToDashboardWithState(Request $request, string $message)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $tab = $request->input('tab', 'all');

        return redirect()->route('dashboard', [
            'month' => $month,
            'year' => $year,
            'tab' => $tab,
        ])->with('success', $message);
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'due_date' => ['required', 'date'],
            'repeat_type' => ['required', Rule::in(['none', 'always', 'installment'])],
            'frequency' => ['nullable', Rule::in(['monthly'])],
            'installments_total' => ['nullable', 'integer', 'min:2', 'max:360'],
        ], [
            'type.required' => 'O tipo é obrigatório.',
            'description.required' => 'A descrição é obrigatória.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.gt' => 'O valor deve ser maior que zero.',
            'due_date.required' => 'A data de vencimento é obrigatória.',
            'repeat_type.required' => 'O tipo de repetição é obrigatório.',
            'installments_total.min' => 'Informe no mínimo 2 parcelas.',
            'installments_total.max' => 'O número de parcelas é muito alto.',
        ]);

        $repeatType = $validated['repeat_type'];
        $installmentsTotal = $repeatType === 'installment'
            ? (int) ($validated['installments_total'] ?? 0)
            : null;

        if ($repeatType === 'installment' && $installmentsTotal < 2) {
            return back()
                ->withErrors(['installments_total' => 'Informe no mínimo 2 parcelas.'])
                ->withInput();
        }

        $baseData = [
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'description' => trim($validated['description']),
            'amount' => $validated['amount'],
            'is_paid' => false,
            'paid_at' => null,
        ];

        $dueDate = Carbon::parse($validated['due_date']);

        DB::transaction(function () use ($repeatType, $installmentsTotal, $baseData, $dueDate) {
            if ($repeatType === 'none') {
                Transaction::create([
                    ...$baseData,
                    'due_date' => $dueDate,
                    'repeat_type' => 'none',
                    'frequency' => null,
                    'installments_total' => null,
                    'installment_number' => null,
                    'parent_transaction_id' => null,
                ]);

                return;
            }

            $count = $this->seriesCount($repeatType, $installmentsTotal);

            $first = Transaction::create([
                ...$baseData,
                'due_date' => $dueDate,
                'repeat_type' => $repeatType,
                'frequency' => 'monthly',
                'installments_total' => $repeatType === 'installment' ? $installmentsTotal : null,
                'installment_number' => $repeatType === 'installment' ? 1 : null,
                'parent_transaction_id' => null,
            ]);

            $first->update([
                'parent_transaction_id' => $first->id,
            ]);

            for ($i = 1; $i < $count; $i++) {
                Transaction::create([
                    ...$baseData,
                    'due_date' => $dueDate->copy()->addMonths($i),
                    'repeat_type' => $repeatType,
                    'frequency' => 'monthly',
                    'installments_total' => $repeatType === 'installment' ? $installmentsTotal : null,
                    'installment_number' => $repeatType === 'installment' ? ($i + 1) : null,
                    'parent_transaction_id' => $first->id,
                ]);
            }
        });

        return $this->redirectToDashboardWithState($request, 'Lançamento salvo com sucesso.');
    }

    public function edit(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'due_date' => ['required', 'date'],
            'repeat_type' => ['required', Rule::in(['none', 'always', 'installment'])],
            'installments_total' => ['nullable', 'integer', 'min:2', 'max:360'],
            'update_scope' => ['nullable', Rule::in(['single', 'future', 'series'])],
        ], [
            'description.required' => 'A descrição é obrigatória.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.gt' => 'O valor deve ser maior que zero.',
            'due_date.required' => 'A data de vencimento é obrigatória.',
            'installments_total.min' => 'Informe no mínimo 2 parcelas.',
            'installments_total.max' => 'O número de parcelas é muito alto.',
        ]);

        $scope = $validated['update_scope'] ?? 'single';
        $newRepeatType = $validated['repeat_type'];
        $newInstallmentsTotal = $newRepeatType === 'installment'
            ? (int) ($validated['installments_total'] ?? 0)
            : null;

        if ($newRepeatType === 'installment' && $newInstallmentsTotal < 2) {
            return back()
                ->withErrors(['installments_total' => 'Informe no mínimo 2 parcelas.'])
                ->withInput();
        }

        $newDueDate = Carbon::parse($validated['due_date']);

        $baseUpdate = [
            'type' => $validated['type'],
            'description' => trim($validated['description']),
            'amount' => $validated['amount'],
        ];

        $structureChanged =
            $transaction->repeat_type !== $newRepeatType ||
            (($transaction->repeat_type === 'installment' || $newRepeatType === 'installment') &&
                (int) ($transaction->installments_total ?? 0) !== (int) ($newInstallmentsTotal ?? 0));

        if ($transaction->is_series && $scope === 'single' && $structureChanged) {
            return back()
                ->withErrors([
                    'repeat_type' => 'Para alterar repetição ou parcelas em um lançamento que faz parte de série, use "esta e futuras" ou "série inteira".'
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $transaction,
            $scope,
            $structureChanged,
            $baseUpdate,
            $newDueDate,
            $newRepeatType,
            $newInstallmentsTotal
        ) {
            if (!$transaction->is_series) {
                $this->updateStandaloneTransaction(
                    $transaction,
                    $baseUpdate,
                    $newDueDate,
                    $newRepeatType,
                    $newInstallmentsTotal
                );
                return;
            }

            if ($scope === 'single') {
                $transaction->update([
                    ...$baseUpdate,
                    'due_date' => $newDueDate,
                ]);
                return;
            }

            if ($scope === 'future') {
                if (!$structureChanged) {
                    $this->updateFutureSameStructure(
                        $transaction,
                        $baseUpdate,
                        $newDueDate
                    );
                } else {
                    $this->rebuildFutureStructure(
                        $transaction,
                        $baseUpdate,
                        $newDueDate,
                        $newRepeatType,
                        $newInstallmentsTotal
                    );
                }
                return;
            }

            if ($scope === 'series') {
                if (!$structureChanged) {
                    $this->updateEntireSeriesSameStructure(
                        $transaction,
                        $baseUpdate,
                        $newDueDate
                    );
                } else {
                    $this->rebuildEntireSeriesStructure(
                        $transaction,
                        $baseUpdate,
                        $newDueDate,
                        $newRepeatType,
                        $newInstallmentsTotal
                    );
                }
            }
        });

        return $this->redirectToDashboardWithState($request, 'Lançamento atualizado com sucesso.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'delete_scope' => ['nullable', Rule::in(['single', 'future', 'series'])],
        ]);

        $scope = $validated['delete_scope'] ?? 'single';

        if (!$transaction->is_series || empty($transaction->parent_transaction_id)) {
            $transaction->delete();

            return $this->redirectToDashboardWithState($request, 'Lançamento excluído com sucesso.');
        }

        $rootId = (int) $transaction->parent_transaction_id;
        $selectedDate = $transaction->due_date->format('Y-m-d');
        $userId = Auth::id();

        DB::transaction(function () use ($transaction, $scope, $rootId, $selectedDate, $userId) {
            $root = Transaction::query()
                ->where('user_id', $userId)
                ->where('id', $rootId)
                ->first();

            if ($scope === 'single') {
                if (!$root || $transaction->id !== $root->id) {
                    $transaction->delete();

                    if ($transaction->repeat_type === 'installment') {
                        $this->renumberInstallmentSeries($rootId, $userId);
                    }

                    return;
                }

                $next = Transaction::query()
                    ->where('user_id', $userId)
                    ->where('parent_transaction_id', $rootId)
                    ->where('id', '!=', $rootId)
                    ->orderBy('due_date')
                    ->orderBy('id')
                    ->first();

                if (!$next) {
                    $transaction->delete();
                    return;
                }

                Transaction::query()
                    ->where('user_id', $userId)
                    ->where('parent_transaction_id', $rootId)
                    ->where('id', '!=', $next->id)
                    ->update([
                        'parent_transaction_id' => $next->id,
                    ]);

                $next->update([
                    'parent_transaction_id' => $next->id,
                ]);

                $root->delete();

                if ($transaction->repeat_type === 'installment') {
                    $this->renumberInstallmentSeries($next->id, $userId);
                }

                return;
            }

            if ($scope === 'future') {
                if ($root && $root->due_date->format('Y-m-d') >= $selectedDate) {
                    $root->delete();
                    return;
                }

                Transaction::query()
                    ->where('user_id', $userId)
                    ->where('parent_transaction_id', $rootId)
                    ->whereDate('due_date', '>=', $selectedDate)
                    ->delete();

                if ($transaction->repeat_type === 'installment') {
                    $this->renumberInstallmentSeries($rootId, $userId);
                }

                return;
            }

            if ($scope === 'series') {
                if ($root) {
                    $root->delete();
                }
                return;
            }

            $transaction->delete();
        });

        return $this->redirectToDashboardWithState($request, 'Lançamento excluído com sucesso.');
    }

    public function markAsPaid(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);

        if (!$transaction->is_paid) {
            $transaction->update([
                'is_paid' => true,
                'paid_at' => now(),
            ]);
        }

        $successMessage = $transaction->type === 'income'
            ? 'Receita marcada como recebida.'
            : 'Despesa marcada como paga.';

        return $this->redirectToDashboardWithState($request, $successMessage);
    }

    public function markAsUnpaid(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);

        if ($transaction->is_paid) {
            $transaction->update([
                'is_paid' => false,
                'paid_at' => null,
            ]);
        }

        $successMessage = $transaction->type === 'income'
            ? 'Receita marcada como pendente.'
            : 'Despesa marcada como pendente.';

        return $this->redirectToDashboardWithState($request, $successMessage);
    }

    private function seriesCount(string $repeatType, ?int $installmentsTotal): int
    {
        return match ($repeatType) {
            'none' => 1,
            'always' => 12,
            'installment' => (int) $installmentsTotal,
            default => 1,
        };
    }

    private function updateStandaloneTransaction(
        Transaction $transaction,
        array $baseUpdate,
        Carbon $newDueDate,
        string $newRepeatType,
        ?int $newInstallmentsTotal
    ): void {
        if ($newRepeatType === 'none') {
            $transaction->update([
                ...$baseUpdate,
                'due_date' => $newDueDate,
                'repeat_type' => 'none',
                'frequency' => null,
                'installments_total' => null,
                'installment_number' => null,
                'parent_transaction_id' => null,
            ]);
            return;
        }

        $transaction->update([
            ...$baseUpdate,
            'due_date' => $newDueDate,
            'repeat_type' => $newRepeatType,
            'frequency' => 'monthly',
            'installments_total' => $newRepeatType === 'installment' ? $newInstallmentsTotal : null,
            'installment_number' => $newRepeatType === 'installment' ? 1 : null,
            'parent_transaction_id' => $transaction->id,
        ]);

        $count = $this->seriesCount($newRepeatType, $newInstallmentsTotal);

        for ($i = 1; $i < $count; $i++) {
            Transaction::create([
                'user_id' => $transaction->user_id,
                'type' => $baseUpdate['type'],
                'description' => $baseUpdate['description'],
                'amount' => $baseUpdate['amount'],
                'due_date' => $newDueDate->copy()->addMonths($i),
                'repeat_type' => $newRepeatType,
                'frequency' => 'monthly',
                'installments_total' => $newRepeatType === 'installment' ? $newInstallmentsTotal : null,
                'installment_number' => $newRepeatType === 'installment' ? ($i + 1) : null,
                'parent_transaction_id' => $transaction->id,
                'is_paid' => false,
                'paid_at' => null,
            ]);
        }
    }

    private function updateFutureSameStructure(
        Transaction $transaction,
        array $baseUpdate,
        Carbon $newDueDate
    ): void {
        $items = Transaction::query()
            ->fromSameSeries($transaction)
            ->whereDate('due_date', '>=', $transaction->due_date->format('Y-m-d'))
            ->orderBy('due_date')
            ->orderBy('id')
            ->get()
            ->values();

        foreach ($items as $index => $item) {
            $item->update([
                ...$baseUpdate,
                'due_date' => $newDueDate->copy()->addMonths($index),
            ]);
        }
    }

    private function updateEntireSeriesSameStructure(
        Transaction $transaction,
        array $baseUpdate,
        Carbon $newDueDate
    ): void {
        $items = Transaction::query()
            ->fromSameSeries($transaction)
            ->orderBy('due_date')
            ->orderBy('id')
            ->get()
            ->values();

        $selectedIndex = $items->search(fn ($item) => $item->id === $transaction->id);
        $selectedIndex = $selectedIndex === false ? 0 : $selectedIndex;

        $seriesStartDate = $newDueDate->copy()->subMonths($selectedIndex);

        foreach ($items as $index => $item) {
            $item->update([
                ...$baseUpdate,
                'due_date' => $seriesStartDate->copy()->addMonths($index),
            ]);
        }
    }

    private function rebuildFutureStructure(
        Transaction $transaction,
        array $baseUpdate,
        Carbon $newDueDate,
        string $newRepeatType,
        ?int $newInstallmentsTotal
    ): void {
        $futureItems = Transaction::query()
            ->fromSameSeries($transaction)
            ->whereDate('due_date', '>=', $transaction->due_date->format('Y-m-d'))
            ->orderBy('due_date')
            ->orderBy('id')
            ->get();

        foreach ($futureItems as $item) {
            if ($item->id !== $transaction->id) {
                $item->delete();
            }
        }

        if ($newRepeatType === 'none') {
            $transaction->update([
                ...$baseUpdate,
                'due_date' => $newDueDate,
                'repeat_type' => 'none',
                'frequency' => null,
                'installments_total' => null,
                'installment_number' => null,
                'parent_transaction_id' => null,
            ]);
            return;
        }

        $transaction->update([
            ...$baseUpdate,
            'due_date' => $newDueDate,
            'repeat_type' => $newRepeatType,
            'frequency' => 'monthly',
            'installments_total' => $newRepeatType === 'installment' ? $newInstallmentsTotal : null,
            'installment_number' => $newRepeatType === 'installment' ? 1 : null,
            'parent_transaction_id' => $transaction->id,
        ]);

        $count = $this->seriesCount($newRepeatType, $newInstallmentsTotal);

        for ($i = 1; $i < $count; $i++) {
            Transaction::create([
                'user_id' => $transaction->user_id,
                'type' => $baseUpdate['type'],
                'description' => $baseUpdate['description'],
                'amount' => $baseUpdate['amount'],
                'due_date' => $newDueDate->copy()->addMonths($i),
                'repeat_type' => $newRepeatType,
                'frequency' => 'monthly',
                'installments_total' => $newRepeatType === 'installment' ? $newInstallmentsTotal : null,
                'installment_number' => $newRepeatType === 'installment' ? ($i + 1) : null,
                'parent_transaction_id' => $transaction->id,
                'is_paid' => false,
                'paid_at' => null,
            ]);
        }
    }

    private function rebuildEntireSeriesStructure(
        Transaction $transaction,
        array $baseUpdate,
        Carbon $newDueDate,
        string $newRepeatType,
        ?int $newInstallmentsTotal
    ): void {
        $seriesItems = Transaction::query()
            ->fromSameSeries($transaction)
            ->orderBy('due_date')
            ->orderBy('id')
            ->get()
            ->values();

        $selectedIndex = $seriesItems->search(fn ($item) => $item->id === $transaction->id);
        $selectedIndex = $selectedIndex === false ? 0 : $selectedIndex;

        if ($newRepeatType === 'none') {
            foreach ($seriesItems as $item) {
                if ($item->id !== $transaction->id) {
                    $item->delete();
                }
            }

            $transaction->update([
                ...$baseUpdate,
                'due_date' => $newDueDate,
                'repeat_type' => 'none',
                'frequency' => null,
                'installments_total' => null,
                'installment_number' => null,
                'parent_transaction_id' => null,
            ]);

            return;
        }

        $count = $this->seriesCount($newRepeatType, $newInstallmentsTotal);

        if ($newRepeatType === 'installment' && $count < ($selectedIndex + 1)) {
            abort(422, 'O novo total de parcelas é menor que a posição da parcela que está sendo editada.');
        }

        $seriesStartDate = $newDueDate->copy()->subMonths($selectedIndex);

        foreach ($seriesItems as $item) {
            if ($item->id !== $transaction->id) {
                $item->delete();
            }
        }

        $transaction->update([
            ...$baseUpdate,
            'due_date' => $seriesStartDate,
            'repeat_type' => $newRepeatType,
            'frequency' => 'monthly',
            'installments_total' => $newRepeatType === 'installment' ? $newInstallmentsTotal : null,
            'installment_number' => $newRepeatType === 'installment' ? 1 : null,
            'parent_transaction_id' => $transaction->id,
            'is_paid' => false,
            'paid_at' => null,
        ]);

        for ($i = 1; $i < $count; $i++) {
            Transaction::create([
                'user_id' => $transaction->user_id,
                'type' => $baseUpdate['type'],
                'description' => $baseUpdate['description'],
                'amount' => $baseUpdate['amount'],
                'due_date' => $seriesStartDate->copy()->addMonths($i),
                'repeat_type' => $newRepeatType,
                'frequency' => 'monthly',
                'installments_total' => $newRepeatType === 'installment' ? $newInstallmentsTotal : null,
                'installment_number' => $newRepeatType === 'installment' ? ($i + 1) : null,
                'parent_transaction_id' => $transaction->id,
                'is_paid' => false,
                'paid_at' => null,
            ]);
        }
    }

    private function renumberInstallmentSeries(int $rootId, int $userId): void
    {
        $items = Transaction::query()
            ->where('user_id', $userId)
            ->where(function ($q) use ($rootId) {
                $q->where('id', $rootId)
                  ->orWhere('parent_transaction_id', $rootId);
            })
            ->where('repeat_type', 'installment')
            ->orderBy('due_date')
            ->orderBy('id')
            ->get()
            ->values();

        if ($items->isEmpty()) {
            return;
        }

        $newRoot = $items->first();
        $newRootId = $newRoot->id;
        $total = $items->count();

        $newRoot->update([
            'parent_transaction_id' => $newRootId,
            'installment_number' => 1,
            'installments_total' => $total,
        ]);

        foreach ($items->slice(1)->values() as $index => $item) {
            $item->update([
                'parent_transaction_id' => $newRootId,
                'installment_number' => $index + 2,
                'installments_total' => $total,
            ]);
        }
    }
}