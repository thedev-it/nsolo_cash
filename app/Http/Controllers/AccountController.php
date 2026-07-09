<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = Account::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('accounts.create');
    }

    public function store(AccountRequest $request): RedirectResponse
    {
        Auth::user()->accounts()->create($request->validated());

        return redirect()->route('accounts.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function edit(Account $account): View
    {
        $this->authorizeOwnership($account);

        return view('accounts.edit', compact('account'));
    }

    public function update(AccountRequest $request, Account $account): RedirectResponse
    {
        $this->authorizeOwnership($account);

        $account->update($request->validated());

        return redirect()->route('accounts.index')
            ->with('success', 'Compte mis à jour.');
    }

    public function destroy(Account $account): RedirectResponse
    {
        $this->authorizeOwnership($account);

        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Compte supprimé.');
    }

    /**
     * Vérifie qu'un compte appartient bien à l'utilisateur connecté.
     * (À terme, on pourra remplacer ça par une vraie Policy Laravel.)
     */
    protected function authorizeOwnership(Account $account): void
    {
        abort_unless($account->user_id === Auth::id(), 403);
    }
}