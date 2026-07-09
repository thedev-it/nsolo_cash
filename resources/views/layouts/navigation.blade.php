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