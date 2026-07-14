<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-end items-center px-4 sm:px-0">
            
            <a href="{{ route('accounts.create') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700 transition">
                <span>+</span> <span class="hidden sm:inline">Nouveau compte</span><span class="inline sm:hidden">Créer</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                @if ($accounts->isEmpty())
                    <div class="p-8 text-center text-gray-500 text-sm sm:text-base">
                        Tu n'as encore aucun compte. Commence par
                        <a href="{{ route('accounts.create') }}" class="text-indigo-600 underline">en créer un</a>.
                    </div>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($accounts as $account)
                            <li class="p-4 flex flex-row items-center justify-between hover:bg-gray-50 transition gap-4">
                                {{-- Infos principales --}}
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">{{ $account->name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-400 capitalize mt-0.5">{{ $account->type }}</p>
                                </div>
                                
                                {{-- Solde et Actions --}}
                                <div class="flex items-center gap-3 sm:gap-6 shrink-0">
                                    <span class="font-bold text-sm sm:text-base {{ $account->balance >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ number_format($account->balance, 0, ',', ' ') }} FCFA
                                    </span>
                                    
                                    <div class="flex items-center gap-1 sm:gap-2">
                                        {{-- Bouton Modifier --}}
                                        <a href="{{ route('accounts.edit', $account) }}"
                                           class="p-1.5 rounded-md text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                           title="Modifier le compte">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Bouton Supprimer --}}
                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                                              data-confirm="Supprimer ce compte et toutes ses transactions ?" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                                    title="Supprimer le compte">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>