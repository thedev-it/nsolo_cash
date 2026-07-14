<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-end items-end px-4 sm:px-0">
            
            <a href="{{ route('saving-goals.create') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700 transition">
                <span>+</span> <span class="hidden sm:inline">Nouveau</span><span class="inline text-center sm:hidden">Créer</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if ($goals->isEmpty())
            <div class="bg-white rounded-xl border border-dashed border-gray-200 p-8 sm:p-10 text-center text-sm text-gray-500">
                Aucun objectif pour l'instant.
                <a href="{{ route('saving-goals.create') }}" class="text-indigo-600 underline block mt-2 sm:inline sm:mt-0">
                    Crée ton premier objectif (vacances, urgence, achat...)
                </a>
            </div>
        @else
            <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($goals as $goal)
                    @php
                        $percent = $goal->progressPercent();
                        $completed = $goal->isCompleted();
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 flex flex-col justify-between shadow-sm">
                        
                        {{-- En-tête de la carte --}}
                        <div class="flex items-start justify-between mb-4 gap-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-lg shrink-0"
                                     style="background-color: {{ $goal->color }}1A">
                                    {{ $completed ? '🎉' : '🎯' }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">{{ $goal->name }}</p>
                                    @if ($goal->target_date)
                                        <p class="text-[11px] sm:text-xs text-gray-400 truncate">
                                            Objectif : {{ $goal->target_date->translatedFormat('d M Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions : Modifier / Supprimer --}}
                            <div class="flex items-center gap-1.5 shrink-0">
                                {{-- Bouton Modifier --}}
                                <a href="{{ route('saving-goals.edit', $goal) }}" 
                                   class="p-1.5 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                   title="Modifier l'objectif">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Bouton Supprimer --}}
                                <form action="{{ route('saving-goals.destroy', $goal) }}" method="POST" data-confirm-delete data-confirm-text="Supprimer cet objectif ?" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 transition"
                                            title="Supprimer l'objectif">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Progression des montants --}}
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs sm:text-sm mb-1.5">
                                <span class="font-medium text-gray-800">
                                    {{ number_format($goal->current_amount, 0, ',', ' ') }} FCFA
                                </span>
                                <span class="text-gray-400">
                                    / {{ number_format($goal->target_amount, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500"
                                     style="width: {{ $percent }}%; background-color: {{ $goal->color }}"></div>
                            </div>
                        </div>

                        {{-- Formulaire de contribution --}}
                        <div class="mt-auto">
                            @if ($completed)
                                <p class="text-sm font-semibold text-center py-2" style="color: {{ $goal->color }}">
                                    Objectif atteint 🎉
                                </p>
                            @else
                                <form action="{{ route('saving-goals.contribute', $goal) }}" method="POST" class="space-y-2">
                                    @csrf

                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="account_id" required
                                                class="w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2">
                                            <option value="">Compte</option>
                                            @foreach (auth()->user()->accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>

                                        <select name="category_id" required
                                                class="w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2">
                                            <option value="">Catégorie</option>
                                            @foreach (auth()->user()->categories->where('type', 'expense') as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="flex gap-2">
                                        <input type="number" name="amount" min="1" step="1" required placeholder="Montant"
                                               class="flex-1 min-w-0 rounded-md border-gray-300 text-xs sm:text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2">
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-gray-900 text-white text-xs font-semibold rounded-md hover:bg-gray-800 transition shrink-0">
                                            Ajouter
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>