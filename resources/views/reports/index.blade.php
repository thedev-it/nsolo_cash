<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rapports</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-1">Générer un rapport mensuel</h3>
            <p class="text-sm text-gray-500 mb-6">
                Le rapport PDF inclut le résumé de vos comptes, dépenses/revenus, budgets et objectifs d'épargne pour le mois sélectionné.
            </p>

            <form action="{{ route('reports.download') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <select name="month"
                        class="flex-1 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($months as $value => $label)
                        <option value="{{ $value }}" @selected($selectedMonth === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Télécharger le PDF
                </button>
            </form>
        </div>
    </div>
</x-app-layout>