<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingsGoalRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SavingsGoalController extends Controller
{
    public function index(): View
    {
        $goals = SavingsGoal::where('user_id', Auth::id())
            ->orderByRaw('current_amount >= target_amount') // objectifs atteints en dernier
            ->orderBy('target_date')
            ->get();

        return view('savings-goals.index', compact('goals'));
    }

    public function create(): View
    {
        return view('savings-goals.create');
    }

    public function store(SavingsGoalRequest $request): RedirectResponse
    {
        Auth::user()->savingsgoals()->create($request->validated());

        return redirect()->route('saving-goals.index')
            ->with('success', 'Objectif d\'épargne créé avec succès.');
    }

    public function edit(SavingsGoal $savingGoal): View
    {
    $this->authorizeOwnership($savingGoal);

    return view('savings-goals.edit', ['goal' => $savingGoal]);
    }

    public function update(SavingsGoalRequest $request, SavingsGoal $savingGoal): RedirectResponse
    {
        $this->authorizeOwnership($savingGoal);
    
        $savingGoal->update($request->validated());
    
        return redirect()->route('saving-goals.index')
            ->with('success', 'Objectif mis à jour.');
    }

    public function destroy(SavingsGoal $savingGoal): RedirectResponse
{
    // Utilisez la variable au singulier ici aussi
    $this->authorizeOwnership($savingGoal);

    $savingGoal->delete();

    return redirect()->route('saving-goals.index')
        ->with('success', 'Objectif supprimé.');
}

    /**
     * Ajoute une contribution manuelle au montant actuel de l'objectif.
     */
    public function contribute(Request $request, SavingsGoal $savingGoal)
{
    abort_unless($savingGoal->user_id === auth()->id(), 403);

    $validated = $request->validate([
        'account_id'  => ['required', 'exists:accounts,id'],
        'category_id' => ['required', 'exists:categories,id'],
        'amount'      => ['required', 'numeric', 'min:1'],
        'description' => ['nullable', 'string', 'max:255'],
    ]);

    // Sécurité : le compte et la catégorie doivent appartenir à l'utilisateur
    $account = Account::where('id', $validated['account_id'])
        ->where('user_id', auth()->id())->firstOrFail();

    $category = Category::where('id', $validated['category_id'])
        ->where('user_id', auth()->id())
        ->where('type', 'expense')
        ->firstOrFail();

    // Crée la vraie transaction (déclenche le TransactionObserver → solde du compte mis à jour)
    Transaction::create([
        'account_id'    => $account->id,
        'category_id'   => $category->id,
        'saving_goal_id'=> $savingGoal->id,
        'type'          => 'expense',
        'amount'        => $validated['amount'],
        'description'   => $validated['description'] ?? "Epargne — {$savingGoal->name}",
        'date'          => now(),
    ]);

    $savingGoal->increment('current_amount', $validated['amount']);

    $reached = $savingGoal->fresh()->current_amount >= $savingGoal->target_amount;

    return back()->with('success', $reached
        ? "🎉 Objectif « {$savingGoal->name} » atteint !"
        : 'Contribution enregistrée, solde du compte mis à jour.');
}

    protected function authorizeOwnership(SavingsGoal $savingsGoal): void
    {
        abort_unless($savingsGoal->user_id === Auth::id(), 403);
    }
}