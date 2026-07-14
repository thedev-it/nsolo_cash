<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-4">
           
            <a href="{{ route('budgets.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700 w-full sm:w-auto text-center">
                + Nouveau budget
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-6">
        {{-- Navigation mois par mois --}}
        <div class="flex items-center justify-between bg-white rounded-xl border border-gray-100 px-6 py-4 shadow-sm">
            <a href="{{ route('budgets.index', ['month' => $previousMonth]) }}"
               class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 shrink-0 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <span class="font-semibold text-gray-900 text-center text-sm sm:text-base capitalize">
                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
            </span>
            <a href="{{ route('budgets.index', ['month' => $nextMonth]) }}"
               class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 shrink-0 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </a>

            <form action="{{ route('budgets.carry-over') }}" method="POST" data-confirm-delete
                data-confirm-text="Reporter le solde non dépensé du mois précédent vers ce mois-ci ?">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium
                                            text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                    ↩️ Reporter les budgets non dépensés
                </button>
            </form>
        </div>

        

        @if ($budgets->isEmpty())
            <div class="bg-white rounded-xl border border-dashed border-gray-200 p-10 text-center text-sm text-gray-500 shadow-sm">
                Aucun budget défini pour ce mois.
                <a href="{{ route('budgets.create') }}" class="text-indigo-600 underline hover:text-indigo-700 ml-1">En créer un</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($budgets as $budget)
                    @php
                        $percent = $budget->amount_limit > 0 ? min(100, round(($budget->spent / $budget->amount_limit) * 100)) : 0;
                        $isOver = $budget->spent > $budget->amount_limit;
                        $barColor = $isOver ? '#EF4444' : ($percent >= 80 ? '#F59E0B' : '#10B981');
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                        {{-- Conteneur Header du Budget --}}
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                            {{-- Catégorie --}}
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $budget->category->color }}"></span>
                                <span class="font-semibold text-gray-900 truncate text-sm sm:text-base">{{ $budget->category->name }}</span>
                            </div>
                            
                            {{-- Montants et Actions --}}
                            <div class="flex items-center justify-between sm:justify-end gap-4 shrink-0">
                                <span class="text-sm {{ $isOver ? 'text-red-600 font-bold' : 'text-gray-500 font-medium' }}">
                                    {{ number_format($budget->spent, 0, ',', ' ') }} / {{ number_format($budget->amount_limit, 0, ',', ' ') }} FCFA
                                </span>
                                
                                <div class="flex items-center gap-2">
                                    {{-- Bouton Modifier (Icône Crayon) --}}
                                    <a href="{{ route('budgets.edit', $budget) }}" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-100 transition-colors shadow-sm"
                                       title="Modifier">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        </svg>
                                    </a>

                                    {{-- Bouton Supprimer (Icône Poubelle) --}}
                                    <form action="{{ route('budgets.destroy', $budget) }}" method="POST" data-confirm="Supprimer ce budget ?">
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
                        </div>

                        {{-- Barre de progression --}}
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%; background-color: {{ $barColor }}"></div>
                        </div>

                        {{-- Alerte Dépassement --}}
                        @if ($isOver)
                            <div class="bg-red-50/50 text-red-700 text-xs rounded-lg px-3 py-2 mt-3 flex items-center gap-2 border border-red-100/50">
                                <span class="shrink-0 text-sm">⚠️</span>
                                <span class="font-medium">Dépassement de budget de {{ number_format($budget->spent - $budget->amount_limit, 0, ',', ' ') }} FCFA</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>