<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Titre de la page --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Analyses</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Vue d'ensemble de vos finances</p>
        </div>

        {{-- Comparaison mois actuel vs précédent --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Carte Dépenses --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col justify-between shadow-sm">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Dépenses — {{ $currentMonth['label'] }}</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">
                        {{ number_format($currentMonth['expense'], 0, ',', ' ') }} FCFA
                    </p>
                </div>
                @php
                    $diff = $previousMonth['expense'] > 0
                        ? (($currentMonth['expense'] - $previousMonth['expense']) / $previousMonth['expense']) * 100
                        : 0;
                @endphp
                <div class="mt-3">
                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full font-semibold {{ $diff > 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                        {{ $diff > 0 ? '▲ +' : '▼' }}{{ number_format($diff, 1) }} %
                        <span class="font-normal text-gray-400">vs {{ $previousMonth['label'] }}</span>
                    </span>
                </div>
            </div>

            {{-- Carte Revenus --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col justify-between shadow-sm">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Revenus — {{ $currentMonth['label'] }}</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">
                        {{ number_format($currentMonth['income'], 0, ',', ' ') }} FCFA
                    </p>
                </div>
                <div class="mt-3">
                    <span class="inline-flex items-center text-xs text-gray-400 py-1">
                        Flux de trésorerie entrant ce mois
                    </span>
                </div>
            </div>
        </div>

        {{-- Section Graphiques --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Répartition par catégorie (Prend 1 colonne sur 3 en grand écran) --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col shadow-sm lg:col-span-1">
                <h2 class="font-semibold text-gray-900 text-sm sm:text-base mb-4">Répartition des dépenses (ce mois)</h2>
                @if($expensesByCategory->isEmpty())
                    <div class="flex-1 flex items-center justify-center min-h-[250px]">
                        <p class="text-sm text-gray-400">Aucune dépense ce mois-ci.</p>
                    </div>
                @else
                    <div class="relative w-full h-64 sm:h-72 mx-auto">
                        <canvas id="categoryChart"></canvas>
                    </div>
                @endif
            </div>

            {{-- Évolution sur 6 mois (Prend 2 colonnes sur 3 en grand écran) --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col shadow-sm lg:col-span-2">
                <h2 class="font-semibold text-gray-900 text-sm sm:text-base mb-4">Évolution sur 6 mois</h2>
                <div class="relative w-full h-64 sm:h-72 lg:h-80">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        @if($expensesByCategory->isNotEmpty())
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($expensesByCategory->pluck('name')),
                datasets: [{
                    data: @json($expensesByCategory->pluck('total')),
                    backgroundColor: @json($expensesByCategory->pluck('color')),
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
        @endif

        new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: @json(collect($monthlyEvolution)->pluck('label')),
                datasets: [
                    {
                        label: 'Revenus',
                        data: @json(collect($monthlyEvolution)->pluck('income')),
                        borderColor: '#10b981',
                        backgroundColor: '#10b98120',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Dépenses',
                        data: @json(collect($monthlyEvolution)->pluck('expense')),
                        borderColor: '#ef4444',
                        backgroundColor: '#ef444420',
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11 }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            font: { size: 10 }
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>