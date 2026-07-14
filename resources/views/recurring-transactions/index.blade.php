<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-4">
        
            <a href="{{ route('recurring-transactions.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700 w-full sm:w-auto text-center">
                + Nouvelle récurrence
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            @if ($recurringTransactions->isEmpty())
                <div class="p-10 text-center text-sm text-gray-500">
                    Aucune transaction récurrente pour l'instant.
                    <a href="{{ route('recurring-transactions.create') }}" class="text-indigo-600 underline">
                        En créer une (loyer, abonnement, salaire...)
                    </a>
                </div>
            @else
                @php
                    $frequencyLabels = ['daily' => 'Quotidien', 'weekly' => 'Hebdomadaire', 'monthly' => 'Mensuel', 'yearly' => 'Annuel'];
                @endphp

                {{-- 1. Version Desktop (Tableau masqué sur mobile) --}}
                <table class="w-full text-sm hidden md:table">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs text-gray-400 uppercase tracking-wide">
                            <th class="px-6 py-3 font-medium">Détails</th>
                            <th class="px-6 py-3 font-medium">Compte</th>
                            <th class="px-6 py-3 font-medium">Fréquence</th>
                            <th class="px-6 py-3 font-medium">Prochaine échéance</th>
                            <th class="px-6 py-3 font-medium text-right">Montant</th>
                            <th class="px-6 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recurringTransactions as $recurring)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $recurring->category->color }}"></span>
                                        <div>
                                            <p class="font-medium text-gray-900 break-all">
                                                {{ $recurring->description ?: $recurring->category->name }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $recurring->category->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $recurring->account->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $frequencyLabels[$recurring->frequency] }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $recurring->next_date->isPast() ? 'text-amber-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $recurring->next_date->translatedFormat('d M Y') }}
                                    </span>
                                    @if ($recurring->next_date->isPast())
                                        <span class="block text-xs text-amber-500">En attente de génération</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-semibold {{ $recurring->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $recurring->type === 'income' ? '+' : '-' }}{{ number_format($recurring->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Bouton Générer (Éclair) --}}
                                        <form action="{{ route('recurring-transactions.generate', $recurring) }}" method="POST" data-confirm="Générer la transaction pour cette échéance ?">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 transition-colors shadow-sm" title="Générer l'échéance">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Bouton Modifier (Crayon) --}}
                                        <a href="{{ route('recurring-transactions.edit', $recurring) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-100 transition-colors shadow-sm" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            </svg>
                                        </a>

                                        {{-- Bouton Supprimer (Poubelle) --}}
                                        <form action="{{ route('recurring-transactions.destroy', $recurring) }}" method="POST" data-confirm="Supprimer cette récurrence ?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-colors shadow-sm" title="Supprimer">
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

                {{-- 2. Version Mobile (Cartes empilées masquées sur desktop) --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($recurringTransactions as $recurring)
                        <div class="p-4 space-y-3 hover:bg-gray-50/50">
                            {{-- Ligne supérieure : Catégorie/Description et Montant --}}
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-2.5 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0 mt-1.5" style="background-color: {{ $recurring->category->color }}"></span>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 break-words leading-tight">
                                            {{ $recurring->description ?: $recurring->category->name }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $recurring->category->name }}</p>
                                    </div>
                                </div>
                                <span class="font-semibold text-sm shrink-0 whitespace-nowrap {{ $recurring->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $recurring->type === 'income' ? '+' : '-' }}{{ number_format($recurring->amount, 0, ',', ' ') }} FCFA
                                </span>
                            </div>

                            {{-- Ligne intermédiaire : Badges Fréquence, Compte et Statut d'Échéance --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs pl-5">
                                <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md font-medium">{{ $frequencyLabels[$recurring->frequency] }}</span>
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md max-w-[120px] truncate">{{ $recurring->account->name }}</span>
                                <span class="text-gray-300">•</span>
                                <div class="inline-flex flex-col">
                                    <span class="{{ $recurring->next_date->isPast() ? 'text-amber-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $recurring->next_date->translatedFormat('d M Y') }}
                                    </span>
                                    @if ($recurring->next_date->isPast())
                                        <span class="text-[10px] text-amber-500 leading-none">En attente</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Ligne inférieure : Bloc d'actions en icônes --}}
                            <div class="flex items-center justify-end gap-2.5 pt-1.5 pl-5 border-t border-gray-50">
                                <form action="{{ route('recurring-transactions.generate', $recurring) }}" method="POST" data-confirm="Générer la transaction pour cette échéance ?">
                                    @csrf
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 shadow-sm" title="Générer l'échéance">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                        </svg>
                                    </button>
                                </form>

                                <a href="{{ route('recurring-transactions.edit', $recurring) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-100 shadow-sm" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                </a>

                                <form action="{{ route('recurring-transactions.destroy', $recurring) }}" method="POST" data-confirm="Supprimer cette récurrence ?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-600 border border-gray-100 shadow-sm" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-1.5 14.25a2.25 2.25 0 01-2.24 2.15H8.24a2.25 2.25 0 01-2.24-2.15L4.5 8.25m17.5 0H2.25m3.375 0h12.75M9 6h6M9 6V4.5a1.5 1.5 0 011.5-1.5h3.5a1.5 1.5 0 011.5 1.5V6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>