<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes catégories</h2>
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700">
                + Nouvelle catégorie
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-10">

        @if (session('success'))
            <div class="p-4 bg-emerald-100 text-emerald-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        @php
            $expenseCategories = $categories->where('type', 'expense');
            $incomeCategories = $categories->where('type', 'income');
        @endphp

        @foreach ([
            ['label' => 'Dépenses', 'items' => $expenseCategories, 'type' => 'expense', 'accent' => '#EF4444', 'tint' => '#FEF2F2'],
            ['label' => 'Revenus', 'items' => $incomeCategories, 'type' => 'income', 'accent' => '#10B981', 'tint' => '#ECFDF5'],
        ] as $group)
            <div class="rounded-2xl border border-gray-100 overflow-hidden">

                {{-- Bandeau de section --}}
                <div class="flex items-center justify-between px-6 py-4" style="background-color: {{ $group['tint'] }}">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center"
                             style="background-color: {{ $group['accent'] }}22">
                            @if ($group['type'] === 'expense')
                                <svg class="w-5 h-5" style="color: {{ $group['accent'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" />
                                </svg>
                            @else
                                <svg class="w-5 h-5" style="color: {{ $group['accent'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0v11.25m0-11.25H8.25" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 leading-tight">{{ $group['label'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $group['items']->count() }} catégorie{{ $group['items']->count() > 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('categories.create') }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-full bg-white/70 hover:bg-white transition"
                       style="color: {{ $group['accent'] }}">
                        + Ajouter
                    </a>
                </div>

                {{-- Contenu de la section --}}
                <div class="p-6 bg-white">
                    @if ($group['items']->isEmpty())
                        <div class="rounded-xl border border-dashed border-gray-200 p-8 text-center text-sm text-gray-400">
                            Rien ici pour l'instant.
                            <a href="{{ route('categories.create') }}" class="text-indigo-600 hover:underline">Ajouter une catégorie</a>
                        </div>
                    @else
                        <div class="grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
                            @foreach ($group['items'] as $category)
                            <div class="group relative rounded-2xl border border-gray-100 p-5 pt-6 bg-white hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

                                {{-- Halo coloré diffus en fond, signature de la catégorie --}}
                                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full opacity-[0.08] pointer-events-none"
                                     style="background-color: {{ $category->color }}"></div>

                                {{-- Actions au survol --}}
                                <div class="absolute top-3 right-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cette catégorie ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-500">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                {{-- Avatar couleur (initiale) --}}
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-base font-bold mb-4 relative z-10"
                                     style="background-color: {{ $category->color }}1A; color: {{ $category->color }}">
                                    {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
                                </div>

                                <p class="font-semibold text-gray-900 truncate relative z-10">{{ $category->name }}</p>
                                <span class="inline-block mt-1.5 text-[11px] font-medium px-2 py-0.5 rounded-full relative z-10"
                                      style="background-color: {{ $category->color }}1A; color: {{ $category->color }}">
                                    {{ $group['type'] === 'expense' ? 'Dépense' : 'Revenu' }}
                                </span>
                            </div>
                        @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>