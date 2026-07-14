<?php

namespace App\View\Components;

use App\Models\Budget;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class NotificationsDropdown extends Component
{
    public array $notifications;

    public function __construct()
    {
        $this->notifications = $this->buildNotifications();
    }

    protected function buildNotifications(): array
    {
        if (! Auth::check()) {
            return [];
        }

        $userId = Auth::id();
        $notifications = [];

        // 1. Budgets dépassés ce mois-ci
        $currentMonth = now()->format('Y-m');
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $budgets = Budget::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->with('category')
            ->get();

        foreach ($budgets as $budget) {
            $spent = (float) Transaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');

            if ($spent > $budget->amount_limit) {
                $notifications[] = [
                    'type' => 'budget_over',
                    'icon' => 'alert',
                    'color' => '#EF4444',
                    'message' => "Budget \"{$budget->category->name}\" dépassé de " . number_format($spent - $budget->amount_limit, 0, ',', ' ') . ' FCFA',
                    'url' => route('budgets.index'),
                ];
            }
        }

        // 2. Récurrences arrivées à échéance
        $dueRecurrences = RecurringTransaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
            ->where('next_date', '<=', now()->toDateString())
            ->with('category')
            ->get();

        foreach ($dueRecurrences as $recurring) {
            $notifications[] = [
                'type' => 'recurring_due',
                'icon' => 'clock',
                'color' => '#F59E0B',
                'message' => ($recurring->description ?: $recurring->category->name) . ' est prête à être générée',
                'url' => route('recurring-transactions.index'),
            ];
        }

        return $notifications;
    }

    public function render(): View
    {
        return view('components.notifications-dropdown');
    }
}