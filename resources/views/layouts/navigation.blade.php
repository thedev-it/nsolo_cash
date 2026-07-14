<div x-data="{ openMobileMenu: false }">
    
    {{-- 1. Bouton Burger (Fixé en haut à gauche sur mobile uniquement) --}}
    <div class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 px-4 flex items-center justify-between z-40">
        <button @click="openMobileMenu = true" class="text-gray-500 hover:text-gray-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <span class="text-lg font-bold text-indigo-600">Nsolo Cash</span>
        <div class="w-6"></div> {{-- Équilibreur visuel --}}
    </div>

    {{-- 2. Arrière-plan sombre (Overlay) pour Mobile --}}
    <div x-show="openMobileMenu" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 lg:hidden"
         @click="openMobileMenu = false"
         x-cloak>
    </div>

    {{-- 3. Le Menu / Drawer (Combiné Desktop fixe + Mobile glissant) --}}
    <aside :class="openMobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 flex flex-col z-50 transform transition-transform duration-300 ease-in-out lg:fixed lg:inset-y-0">

        {{-- Entête du Drawer --}}
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600 no-underline">
                Nsolo Cash
            </a>
            {{-- Bouton de fermeture sur mobile --}}
            <button @click="openMobileMenu = false" class="lg:hidden text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Liens de navigation --}}
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Tableau de bord', 'pattern' => 'dashboard'],
                    ['route' => 'accounts.index', 'label' => 'Mes comptes', 'pattern' => 'accounts.*'],
                    ['route' => 'categories.index', 'label' => 'Catégories', 'pattern' => 'categories.*'],
                    ['route' => 'transactions.index', 'label' => 'Transactions', 'pattern' => 'transactions.*'],
                    ['route' => 'budgets.index', 'label' => 'Budgets', 'pattern' => 'budgets.*'],
                    ['route' => 'recurring-transactions.index', 'label' => 'Récurrences', 'pattern' => 'recurring-transactions.*'],
                    ['route' => 'saving-goals.index', 'label' => 'Épargne', 'pattern' => 'savings-goals.*'],
                    ['route' => 'analytics.index', 'label' => 'Mes Satistiques', 'pattern' => 'analytic.*'],
                    ['route' => 'reports.index', 'label' => 'Rapports', 'pattern' => 'reports.*'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['pattern']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition no-underline
                          {{ $active ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">

                    @switch($item['route'])
                        @case('dashboard')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75a3 3 0 013-3h10.5a3 3 0 013 3v10.5a3 3 0 01-3 3H6.75a3 3 0 01-3-3V6.75z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15M15 4.5v15" opacity=".4"/>
                            </svg>
                            @break
                        @case('accounts.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 8.25v10.5a1.5 1.5 0 001.5 1.5h16.5a1.5 1.5 0 001.5-1.5V8.25M2.25 8.25l1.72-3.44A1.5 1.5 0 015.32 4h13.36a1.5 1.5 0 011.35.81l1.72 3.44" />
                            </svg>
                            @break
                        @case('categories.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.83.699 2.528 0l7.023-7.023c.699-.699.699-1.83 0-2.528l-9.581-9.581A2.25 2.25 0 0011.318 3H9.568z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            @break
                        @case('transactions.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                            @break
                        @case('budgets.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            @break
                        @case('saving-goals.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                          @break
                        @case('recurring-transactions.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            @break

                        @case('analytics.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                            @break
                        @case('reports.index')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            @break
                    @endswitch

                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Pied du Drawer --}}
        <div class="border-t border-gray-100 p-3 space-y-1">
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 no-underline">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Mon profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>
</div>