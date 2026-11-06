<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionExportController extends Controller
{
    public function excel(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $tab = $request->get('tab', 'all');

        $filename = 'moneti-' . $year . '-' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '-' . $tab . '.xlsx';

        return Excel::download(
            new TransactionsExport(Auth::id(), $month, $year, $tab),
            $filename
        );
    }

    public function pdf(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $tab = $request->get('tab', 'all');

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();

        $query = Transaction::query()
            ->where('user_id', Auth::id())
            ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('due_date')
            ->orderByDesc('id');

        if ($tab === 'income') {
            $query->where('type', 'income');
        }

        if ($tab === 'expense') {
            $query->where('type', 'expense');
        }

        $transactions = $query->get();

        $incomeTotal = Transaction::query()
            ->where('user_id', Auth::id())
            ->where('type', 'income')
            ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $expenseTotal = Transaction::query()
            ->where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $balance = $incomeTotal - $expenseTotal;
        $monthName = ucfirst(Carbon::create($year, $month, 1)->translatedFormat('F/Y'));

        $pdf = Pdf::loadView('exports.transactions-pdf', [
            'transactions' => $transactions,
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balance' => $balance,
            'monthName' => $monthName,
            'tab' => $tab,
        ])->setPaper('a4', 'portrait');

        $filename = 'moneti-' . $year . '-' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '-' . $tab . '.pdf';

        return $pdf->download($filename);
    }
}