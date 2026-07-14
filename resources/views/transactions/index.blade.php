<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-4">
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700 w-full sm:w-auto text-center">
                + Nouvelle transaction
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            @if ($transactions->isEmpty())
                <div class="p-10 text-center text-sm text-gray-500">
                    Aucune transaction pour l'instant.
                    <a href="{{ route('transactions.create') }}" class="text-indigo-600 underline">
                        Ajoute ta première transaction
                    </a>
                </div>
            @else
                {{-- 1. Version Desktop (Tableau visible uniquement sur md et plus) --}}
                <table class="w-full text-sm hidden md:table">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs text-gray-400 uppercase tracking-wide">
                            <th class="px-6 py-3 font-medium">Détails</th>
                            <th class="px-6 py-3 font-medium">Compte</th>
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium text-right">Montant</th>
                            <th class="px-6 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $transaction->category->color }}"></span>
                                        <div>
                                            <p class="font-medium text-gray-900 break-all">
                                                {{ $transaction->description ?: $transaction->category->name }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $transaction->category->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $transaction->account->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $transaction->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right font-semibold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <div class="flex items-center justify-end gap-2.5">
                                            {{-- Bouton Modifier (Icône Crayon) --}}
                                            <a href="{{ route('transactions.edit', $transaction) }}" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-100 transition-colors shadow-sm"
                                            title="Modifier">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                </svg>
                                            </a>
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" data-confirm="Supprimer cette transaction ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-colors shadow-sm"
                                                title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-1.5 14.25a2.25 2.25 0 01-2.24 2.15H8.24a2.25 2.25 0 01-2.24-2.15L4.5 8.25m17.5 0H2.25m3.375 0h12.75M9 6h6M9 6V4.5a1.5 1.5 0 011.5-1.5h3.5a1.5 1.5 0 011.5 1.5V6" />
                                            </svg>
                                             </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- 2. Version Mobile (Liste adaptative masquée sur md et plus) --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($transactions as $transaction)
                        <div class="p-4 space-y-3 hover:bg-gray-50/50">
                            {{-- Ligne supérieure : Catégorie/Description et Montant --}}
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-2.5 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0 mt-1.5" style="background-color: {{ $transaction->category->color }}"></span>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 break-words leading-tight">
                                            {{ $transaction->description ?: $transaction->category->name }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $transaction->category->name }}</p>
                                    </div>
                                </div>
                                <span class="font-semibold text-sm shrink-0 whitespace-nowrap {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                </span>
                            </div>

                            {{-- Ligne intermédiaire : Métadonnées du compte et de la date --}}
                            <div class="flex items-center gap-2 text-xs text-gray-500 pl-5">
                                <span class="bg-gray-100 px-2 py-0.5 rounded-md truncate max-w-[150px]">{{ $transaction->account->name }}</span>
                                <span class="text-gray-300">•</span>
                                <span>{{ $transaction->date->format('d M Y') }}</span>
                            </div>

                            {{-- Ligne inférieure : Actions --}}
                            <div class="flex items-center justify-end gap-4 pt-1 pl-5 text-sm border-t border-gray-50">
                                <div class="flex items-center justify-end gap-2.5">
                                {{-- Bouton Modifier (Icône Crayon) --}}
                                <a href="{{ route('transactions.edit', $transaction) }}" 
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-100 transition-colors shadow-sm"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" data-confirm="Supprimer cette transaction ?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-colors shadow-sm"
                                        title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-1.5 14.25a2.25 2.25 0 01-2.24 2.15H8.24a2.25 2.25 0 01-2.24-2.15L4.5 8.25m17.5 0H2.25m3.375 0h12.75M9 6h6M9 6V4.5a1.5 1.5 0 011.5-1.5h3.5a1.5 1.5 0 011.5 1.5V6" />
                                    </svg>
                                </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>