@props(['title' => 'Bienvenue', 'subtitle' => null])

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nsolo Cash</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    <div class="min-h-screen flex">

        {{-- Panneau de gauche : identité / signature --}}
        <div class="hidden lg:flex lg:w-[42%] relative overflow-hidden bg-[#12103A] text-white flex-col justify-between p-12">

            <a href="{{ route('landing') }}" class="text-2xl font-bold relative z-10">
                Nsolo<span class="text-emerald-400">Cash</span>
            </a>

            <div class="relative z-10">
                <h1 class="text-4xl font-bold leading-tight mb-4">
                    {{ $title }}
                </h1>
                <p class="text-[#B9B6DA] text-base max-w-sm">
                    {{ $subtitle ?? "Ton argent, visible d'un coup d'œil. Comptes, budgets et dépenses réunis au même endroit." }}
                </p>
            </div>

            {{-- Courbe de croissance : signature visuelle de la page --}}
            <svg class="absolute inset-x-0 bottom-0 w-full h-[45%] z-0" viewBox="0 0 500 240" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="growthFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#34D399" stop-opacity="0.25" />
                        <stop offset="100%" stop-color="#34D399" stop-opacity="0" />
                    </linearGradient>
                </defs>
                <path d="M0,200 C60,190 90,160 130,150 C170,140 190,175 230,160 C280,142 300,90 350,70 C400,50 430,80 500,20 L500,240 L0,240 Z"
                      fill="url(#growthFill)" />
                <path d="M0,200 C60,190 90,160 130,150 C170,140 190,175 230,160 C280,142 300,90 350,70 C400,50 430,80 500,20"
                      fill="none" stroke="#34D399" stroke-width="2.5" />
                <circle cx="130" cy="150" r="4" fill="#34D399" />
                <circle cx="230" cy="160" r="4" fill="#34D399" />
                <circle cx="350" cy="70" r="4" fill="#34D399" />
                <circle cx="500" cy="20" r="5" fill="#34D399" />
            </svg>
        </div>

        {{-- Panneau de droite : formulaire --}}
        <div class="flex-1 flex flex-col justify-center items-center bg-[#FAFAF9] px-6 py-12">
            <div class="w-full max-w-sm">

                {{-- Logo visible uniquement en mobile (le panneau gauche est caché) --}}
                <a href="{{ route('landing') }}" class="lg:hidden block text-center font-display text-2xl mb-8 text-[#12103A]">
                    Nsolo<span class="text-emerald-500">Cash</span>
                </a>

                {{ $slot }}
            </div>
        </div>

    </div>
</body>
</html>