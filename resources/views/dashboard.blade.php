<x-app-layout>

    <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8 px-4 sm:px-6 lg:px-8 pb-24">

        @if (session('status'))
            <div class="p-4 bg-emerald-100 text-emerald-800 rounded-md text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Titre de bienvenue adapté aux mobiles --}}
        <div class="pt-4 lg:pt-0">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Bienvenue, {{ explode(' ', auth()->user()->name)[0] }}</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Suis l'évolution de tes finances en temps réel.</p>
        </div>

        {{-- Cartes résumé : 1 col sur mobile, 2 col sur tablette, 4 col sur desktop --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 8.25v10.5a1.5 1.5 0 001.5 1.5h16.5a1.5 1.5 0 001.5-1.5V8.25M2.25 8.25l1.72-3.44A1.5 1.5 0 015.32 4h13.36a1.5 1.5 0 011.35.81l1.72 3.44" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400">Solde total</span>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($totalBalance, 2) }} FCFA</p>
                <p class="text-xs text-gray-400 mt-1">{{ $accounts->count() }} compte(s)</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l6-6m0 0l6 6m-6-6V4.5" transform="rotate(180 12 12)"/>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400">Ce mois-ci</span>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-red-500">{{ number_format($monthExpenses, 2) }} FCFA</p>
                <p class="text-xs text-gray-400 mt-1">Dépenses</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l6-6m0 0l6 6m-6-6V4.5"/>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400">Ce mois-ci</span>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-emerald-500">{{ number_format($monthIncome, 2) }} FCFA</p>
                <p class="text-xs text-gray-400 mt-1">Revenus</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="w-9 h-9 rounded-lg bg-violet-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400">Solde net</span>
                </div>
                <p class="text-xl sm:text-2xl font-bold {{ ($monthIncome - $monthExpenses) >= 0 ? 'text-gray-900' : 'text-red-500' }}">
                    {{ number_format($monthIncome - $monthExpenses, 2) }} FCFA
                </p>
                <p class="text-xs text-gray-400 mt-1">Ce mois-ci</p>
            </div>
        </div>

        {{-- Tableau des dernières transactions optimisé pour mobile --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Dernières transactions</h3>
                <a href="{{ route('transactions.index') }}" class="text-xs sm:text-sm text-indigo-600 hover:underline no-underline">Voir tout</a>
            </div>

            @if ($recentTransactions->isEmpty())
                <div class="p-8 sm:p-10 text-center text-sm text-gray-500">
                    Aucune transaction pour l'instant.
                    <br><span class="text-xs text-gray-400">Le module Transactions arrive bientôt 🚧</span>
                </div>
            @px
            @else
                {{-- Conteneur de défilement horizontal obligatoire pour les petits écrans --}}
                <div class="w-full overflow-x-auto whitespace-nowrap min-w-full inline-block align-middle">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-400 uppercase tracking-wide bg-gray-50/50">
                                <th class="px-4 sm:px-6 py-3 font-medium">Détails</th>
                                <th class="px-4 sm:px-6 py-3 font-medium">Compte</th>
                                <th class="px-4 sm:px-6 py-3 font-medium">Date</th>
                                <th class="px-4 sm:px-6 py-3 font-medium text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($recentTransactions as $transaction)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-4 sm:px-6 py-4">
                                        <p class="font-medium text-gray-900 max-w-[180px] sm:max-w-none truncate">
                                            {{ $transaction->description ?: $transaction->category->name }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $transaction->category->name }}</p>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-gray-500 text-xs sm:text-sm">{{ $transaction->account->name }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-gray-500 text-xs sm:text-sm">{{ $transaction->date->format('d M Y') }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-right font-semibold text-xs sm:text-sm {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Budgets du mois --}}
        <div class="bg-white rounded-xl border border-gray-100">
            <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Budgets de ce mois-ci</h3>
                <a href="{{ route('budgets.index') }}" class="text-xs sm:text-sm text-indigo-600 hover:underline no-underline">Voir tout</a>
            </div>

            @if ($budgets->isEmpty())
                <div class="p-8 sm:p-10 text-center text-sm text-gray-500">
                    Aucun budget défini pour ce mois.
                    <a href="{{ route('budgets.create') }}" class="text-indigo-600 underline px-1 py-0.5 no-underline">En créer un</a>
                </div>
            @else
                <div class="p-4 sm:p-6 space-y-4">
                    @foreach ($budgets as $budget)
                        @php
                            $percent = $budget->amount_limit > 0 ? min(100, round(($budget->spent / $budget->amount_limit) * 100)) : 0;
                            $isOver = $budget->spent > $budget->amount_limit;
                            $barColor = $isOver ? '#EF4444' : ($percent >= 80 ? '#F59E0B' : '#10B981');
                        @endphp
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1.5 text-xs sm:text-sm gap-0.5 sm:gap-0">
                                <span class="font-medium text-gray-800">{{ $budget->category->name }}</span>
                                <span class="{{ $isOver ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                    {{ number_format($budget->spent, 2) }} FCFA / {{ number_format($budget->amount_limit, 2) }} FCFA
                                </span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%; background-color: {{ $barColor }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Comptes --}}
        <div class="bg-white rounded-xl border border-gray-100">
            <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Mes comptes</h3>
                <a href="{{ route('accounts.create') }}" class="text-xs sm:text-sm text-indigo-600 hover:underline no-underline">+ Ajouter</a>
            </div>

            @if ($accounts->isEmpty())
                <div class="p-8 sm:p-10 text-center text-sm text-gray-500">
                    Aucun compte pour le moment.
                    <a href="{{ route('accounts.create') }}" class="text-indigo-600 underline px-1 py-0.5 no-underline">Créer ton premier compte</a>
                </div>
            @else
                <ul class="divide-y divide-gray-50">
                    @foreach ($accounts as $account)
                        <li class="px-4 sm:px-6 py-4 flex items-center justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-900">{{ $account->name }}</p>
                                <p class="text-[10px] sm:text-xs text-gray-400 capitalize">{{ $account->type }}</p>
                            </div>
                            <span class="text-xs sm:text-sm font-semibold {{ $account->balance >= 0 ? 'text-gray-900' : 'text-red-500' }}">
                                {{ number_format($account->balance, 2) }} FCFA
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Bouton flottant ajusté pour le pouce --}}
    <a href="{{ route('transactions.create') }}"
       class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 w-14 h-14 bg-indigo-600 hover:bg-indigo-700 rounded-full shadow-xl flex items-center justify-center text-white transition active:scale-95 z-40">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </a>

</x-app-layout>