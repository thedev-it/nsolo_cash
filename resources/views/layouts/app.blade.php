<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nzolo Cash</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (session('success'))
        <script>window.flashSuccess = @json(session('success'));</script>
    @endif
    @if (session('status'))
        <script>window.flashStatus = @json(session('status'));</script>
    @endif
</head>
<body class="font-sans antialiased bg-gray-50">

    @include('layouts.navigation')

    <div class="lg:pl-64">

        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-10">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="text" placeholder="Rechercher une transaction..."
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-full border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
            </div>

            <div class="flex items-center gap-5">
                <x-notifications-dropdown />

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>

        @isset($header)
            <div class="bg-white border-b border-gray-100 px-6 py-4">
                {{ $header }}
            </div>
        @endisset

        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>