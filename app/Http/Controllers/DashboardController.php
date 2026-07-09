<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        $accounts = Account::where('user_id', $userId)->orderBy('name')->get();
        $totalBalance = $accounts->sum('balance');

        // Transactions du mois en cours, pour les user à travers leurs comptes
        $monthTransactions = Transaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->get();

        $monthExpenses = $monthTransactions->where('type', 'expense')->sum('amount');
        $monthIncome = $monthTransactions->where('type', 'income')->sum('amount');

        $recentTransactions = Transaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
            ->with(['account', 'category'])
            ->latest('date')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'accounts',
            'totalBalance',
            'monthExpenses',
            'monthIncome',
            'recentTransactions',
        ));
    }
}