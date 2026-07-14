<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\SavingGoal;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $months = $this->availableMonths();
        $selectedMonth = $request->input('month', now()->format('Y-m'));

        return view('reports.index', compact('months', 'selectedMonth'));
    }

    public function download(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->buildReportData($month);

        $pdf = Pdf::loadView('reports.pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download("rapport-{$month}.pdf");
    }

    private function buildReportData(string $month): array
    {
        $userId = Auth::id();
        $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $accounts = Account::where('user_id', $userId)->orderBy('name')->get();
        $totalBalance = $accounts->sum('balance');

        $transactions = Transaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
            ->whereYear('date', $period->year)
            ->whereMonth('date', $period->month)
            ->with(['category', 'account'])
            ->orderByDesc('date')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');

        $expensesByCategory = $transactions->where('type', 'expense')
            ->groupBy(fn ($t) => $t->category->name ?? 'Sans catégorie')
            ->map(fn ($group) => $group->sum('amount'))
            ->sortDesc();

        $budgets = Budget::where('user_id', $userId)
            ->where('month', $month)
            ->with('category')
            ->get()
            ->map(function ($budget) use ($transactions) {
                $spent = $transactions->where('type', 'expense')
                    ->where('category_id', $budget->category_id)
                    ->sum('amount');

                return [
                    'category' => $budget->category->name ?? 'Sans catégorie',
                    'limit' => $budget->amount_limit,
                    'spent' => $spent,
                    'remaining' => $budget->amount_limit - $spent,
                    'percent' => $budget->amount_limit > 0
                        ? min(100, round(($spent / $budget->amount_limit) * 100))
                        : 0,
                ];
            });

        $savingGoals = SavingsGoal::where('user_id', $userId)->orderBy('name')->get();

        return [
            'appName' => config('app.name', 'Nzolo Cash'),
            'user' => Auth::user(),
            'period' => $period,
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'expensesByCategory' => $expensesByCategory,
            'budgets' => $budgets,
            'savingGoals' => $savingGoals,
            'transactions' => $transactions->take(40),
            'transactionsCount' => $transactions->count(),
            'generatedAt' => now(),
        ];
    }

    private function availableMonths(): array
    {
        $months = [];

        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $months[$date->format('Y-m')] = ucfirst($date->translatedFormat('F Y'));
        }

        return $months;
    }
}