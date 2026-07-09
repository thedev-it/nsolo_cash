<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $transactions = Transaction::whereHas('account', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['account', 'category'])
            ->latest('date')
            ->latest('id')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    public function create(): View
    {
        [$accounts, $categories] = $this->formData();

        return view('transactions.create', compact('accounts', 'categories'));
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        Transaction::create($request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction ajoutée avec succès.');
    }

    public function edit(Transaction $transaction): View
    {
        $this->authorizeOwnership($transaction);

        [$accounts, $categories] = $this->formData();

        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(TransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $this->authorizeOwnership($transaction);

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction mise à jour.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorizeOwnership($transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction supprimée.');
    }

    /**
     * Vérifie que la transaction appartient bien (via son compte) à l'utilisateur connecté.
     */
    protected function authorizeOwnership(Transaction $transaction): void
    {
        abort_unless($transaction->account->user_id === Auth::id(), 403);
    }

    /**
     * Comptes et catégories de l'utilisateur, pour peupler les formulaires.
     */
    protected function formData(): array
    {
        $userId = Auth::id();

        $accounts = Account::where('user_id', $userId)->orderBy('name')->get();
        $categories = Category::where('user_id', $userId)->orderBy('type')->orderBy('name')->get();

        return [$accounts, $categories];
    }
}