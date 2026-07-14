<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Répartition des dépenses par catégorie (mois en cours)
        $expensesByCategory = Transaction::query()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->whereHas('account', fn ($q) => $q->where('user_id', $userId))
            ->where('transactions.type', 'expense')
            ->whereMonth('transactions.date', now()->month)
            ->whereYear('transactions.date', now()->year)
            ->selectRaw('categories.name, categories.color, SUM(transactions.amount) as total')
            ->groupBy('categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get();

        // 2. Évolution revenus/dépenses sur les 6 derniers mois
        $monthlyEvolution = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);

            $income = Transaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
                ->where('type', 'income')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $expense = Transaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
                ->where('type', 'expense')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $monthlyEvolution[] = [
                'label' => ucfirst($month->translatedFormat('M Y')),
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        }

        // 3. Comparaison mois actuel vs mois précédent
        $current = $monthlyEvolution[5];
        $previous = $monthlyEvolution[4];

        return view('analytics.index', [
            'expensesByCategory' => $expensesByCategory,
            'monthlyEvolution' => $monthlyEvolution,
            'currentMonth' => $current,
            'previousMonth' => $previous,
        ]);
    }
}