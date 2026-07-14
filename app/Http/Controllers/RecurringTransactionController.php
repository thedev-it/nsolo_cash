<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecurringTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecurringTransactionController extends Controller
{
    public function index(): View
    {
        $recurringTransactions = RecurringTransaction::whereHas('account', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['account', 'category'])
            ->orderBy('next_date')
            ->get();

        return view('recurring-transactions.index', compact('recurringTransactions'));
    }

    public function create(): View
    {
        [$accounts, $categories] = $this->formData();

        return view('recurring-transactions.create', compact('accounts', 'categories'));
    }

    public function store(RecurringTransactionRequest $request): RedirectResponse
    {
        RecurringTransaction::create($request->validated());

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente créée avec succès.');
    }

    public function edit(RecurringTransaction $recurringTransaction): View
    {
        $this->authorizeOwnership($recurringTransaction);

        [$accounts, $categories] = $this->formData();

        return view('recurring-transactions.edit', [
            'recurringTransaction' => $recurringTransaction,
            'accounts' => $accounts,
            'categories' => $categories,
        ]);
    }

    public function update(RecurringTransactionRequest $request, RecurringTransaction $recurringTransaction): RedirectResponse
    {
        $this->authorizeOwnership($recurringTransaction);

        $recurringTransaction->update($request->validated());

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente mise à jour.');
    }

    public function destroy(RecurringTransaction $recurringTransaction): RedirectResponse
    {
        $this->authorizeOwnership($recurringTransaction);

        $recurringTransaction->delete();

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente supprimée.');
    }

    /**
     * Génère une vraie transaction à partir de la récurrence, puis avance
     * la prochaine échéance selon la fréquence choisie.
     */
    public function generate(RecurringTransaction $recurringTransaction): RedirectResponse
    {
        $this->authorizeOwnership($recurringTransaction);

        Transaction::create([
            'account_id' => $recurringTransaction->account_id,
            'category_id' => $recurringTransaction->category_id,
            'type' => $recurringTransaction->type,
            'amount' => $recurringTransaction->amount,
            'description' => $recurringTransaction->description,
            'date' => $recurringTransaction->next_date,
        ]);

        $recurringTransaction->update([
            'next_date' => match ($recurringTransaction->frequency) {
                'daily' => $recurringTransaction->next_date->addDay(),
                'weekly' => $recurringTransaction->next_date->addWeek(),
                'monthly' => $recurringTransaction->next_date->addMonth(),
                'yearly' => $recurringTransaction->next_date->addYear(),
            },
        ]);

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Transaction générée, prochaine échéance mise à jour.');
    }

    protected function authorizeOwnership(RecurringTransaction $recurringTransaction): void
    {
        abort_unless($recurringTransaction->account->user_id === Auth::id(), 403);
    }

    protected function formData(): array
    {
        $userId = Auth::id();

        $accounts = Account::where('user_id', $userId)->orderBy('name')->get();
        $categories = Category::where('user_id', $userId)->orderBy('type')->orderBy('name')->get();

        return [$accounts, $categories];
    }
}