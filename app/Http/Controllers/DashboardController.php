<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $tab = $request->get('tab', 'all');

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();

        $query = Transaction::where('user_id', auth()->id())
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

        $incomeTotal = Transaction::where('user_id', auth()->id())
            ->where('type', 'income')
            ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $expenseTotal = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $balance = $incomeTotal - $expenseTotal;

        $currentDate = Carbon::create($year, $month, 1);

        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        return view('dashboard', [
            'transactions' => $transactions,
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balance' => $balance,
            'month' => $month,
            'year' => $year,
            'tab' => $tab,
            'monthName' => ucfirst($currentDate->translatedFormat('F/Y')),
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
        ]);
    }
}