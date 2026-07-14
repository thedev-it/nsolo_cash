<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $month = $request->get('month', now()->format('Y-m'));

        $budgets = Budget::where('user_id', $userId)
            ->where('month', $month)
            ->with('category')
            ->get()
            ->map(function (Budget $budget) use ($userId, $month) {
                $budget->spent = $this->spentForCategory($userId, $budget->category_id, $month);
                return $budget;
            });

        $previousMonth = Carbon::createFromFormat('Y-m', $month)->subMonth()->format('Y-m');
        $nextMonth = Carbon::createFromFormat('Y-m', $month)->addMonth()->format('Y-m');

        return view('budgets.index', compact('budgets', 'month', 'previousMonth', 'nextMonth'));
    }

    public function create(): View
    {
        $categories = $this->expenseCategories();

        return view('budgets.create', compact('categories'));
    }

    public function store(BudgetRequest $request): RedirectResponse
    {
        Auth::user()->budgets()->create($request->validated());

        return redirect()->route('budgets.index', ['month' => $request->input('month')])
            ->with('success', 'Budget créé avec succès.');
    }

    public function edit(Budget $budget): View
    {
        $this->authorizeOwnership($budget);

        $categories = $this->expenseCategories();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(BudgetRequest $request, Budget $budget): RedirectResponse
    {
        $this->authorizeOwnership($budget);

        $budget->update($request->validated());

        return redirect()->route('budgets.index', ['month' => $budget->month])
            ->with('success', 'Budget mis à jour.');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        $this->authorizeOwnership($budget);

        $month = $budget->month;
        $budget->delete();

        return redirect()->route('budgets.index', ['month' => $month])
            ->with('success', 'Budget supprimé.');
    }

    protected function authorizeOwnership(Budget $budget): void
    {
        abort_unless($budget->user_id === Auth::id(), 403);
    }

    protected function expenseCategories()
    {
        return Category::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();
    }

    /**
     * Somme des dépenses d'une catégorie donnée, sur un mois donné (format Y-m),
     * pour les comptes appartenant à l'utilisateur.
     */
    protected function spentForCategory(int $userId, int $categoryId, string $month): float
    {
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        return (float) Transaction::whereHas('account', fn ($q) => $q->where('user_id', $userId))
            ->where('category_id', $categoryId)
            ->where('type', 'expense')
            ->whereBetween('date', [$start, $end])
            ->sum('amount');
    }

    public function carryOver(Request $request)
{
    $targetMonth = $request->input('month', now()->format('Y-m'));
    $previousMonth = Carbon::createFromFormat('Y-m', $targetMonth)->subMonth()->format('Y-m');

    $previousBudgets = Budget::where('user_id', auth()->id())
        ->where('month', $previousMonth)
        ->get();

    $carriedCount = 0;

    foreach ($previousBudgets as $prevBudget) {
        $spent = Transaction::where('category_id', $prevBudget->category_id)
            ->whereHas('account', fn ($q) => $q->where('user_id', auth()->id()))
            ->where('type', 'expense')
            ->whereYear('date', substr($previousMonth, 0, 4))
            ->whereMonth('date', substr($previousMonth, 5, 2))
            ->sum('amount');

        $leftover = $prevBudget->amount_limit - $spent;

        if ($leftover <= 0) {
            continue; // rien à reporter si dépassé ou pile atteint
        }

        $currentBudget = Budget::firstOrNew([
            'user_id' => auth()->id(),
            'category_id' => $prevBudget->category_id,
            'month' => $targetMonth,
        ]);

        $currentBudget->amount_limit = $currentBudget->exists
            ? $currentBudget->amount_limit + $leftover
            : $leftover;

        $currentBudget->save();
        $carriedCount++;
    }

    return back()->with('success', $carriedCount > 0
        ? "$carriedCount budget(s) reporté(s) depuis le mois précédent."
        : 'Aucun solde à reporter (tout a été dépensé ou dépassé le mois dernier).');
}
}