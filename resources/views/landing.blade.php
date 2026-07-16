<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nsolo-cash</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 min-h-full flex flex-col">

    {{-- Navbar --}}
    <header class="border-b border-gray-200 bg-white sticky top-0 z-50 shadow-sm">
        <nav class="max-w-6xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                
                {{-- Logo --}}
                <div class="inline-flex items-center gap-2 shrink-0">
                    <span class="text-2xl sm:text-3xl font-light text-slate-800 tracking-tight">Nsolo<span class="font-extrabold text-indigo-600">Cash</span></span>
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                
                {{-- Menu Desktop (Masqué sur mobile) --}}
                <div class="hidden md:flex items-center gap-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-600 transition">
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-600 transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-xs font-semibold bg-indigo-600 text-white px-4 py-2.5 rounded-full hover:bg-indigo-700 shadow-sm transition">
                            Créer un compte
                        </a>
                    @endauth
                </div>

                {{-- Bouton Burger Mobile (Masqué sur desktop) --}}
                <button id="burger-btn" type="button" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Ouvrir le menu">
                    <svg id="burger-icon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Dropdown Menu Mobile --}}
            <div id="mobile-menu" class="hidden md:hidden pt-4 pb-2 border-t border-gray-100 mt-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-center text-sm font-semibold text-white bg-indigo-600 px-4 py-3 rounded-xl hover:bg-indigo-700 shadow-sm transition">
                        Tableau de bord
                    </a>
                @else
                    <div class="flex flex-col gap-2.5">
                        {{-- Connexion : Bouton secondaire --}}
                        <a href="{{ route('login') }}" class="block text-center text-sm font-semibold text-indigo-600 bg-indigo-50/70 hover:bg-indigo-50 px-4 py-3 rounded-xl transition">
                            Connexion
                        </a>
                        {{-- Créer un compte : Bouton principal --}}
                        <a href="{{ route('register') }}" class="block text-center text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-3 rounded-xl shadow-sm transition">
                            Créer un compte
                        </a>
                    </div>
                @endauth
            </div>
        </nav>
    </header>

    <main class="flex-1 flex flex-col justify-center">
        {{-- Hero --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-24 text-center">
            <h1 class="text-3xl sm:text-5xl font-bold tracking-tight text-slate-900 leading-tight">
               Contrôle ton budget <br class="hidden sm:block"> avec
                <span class="text-indigo-600 block sm:inline mt-1 sm:mt-0">Nsolo Cash</span>
            </h1>
            <p class="mt-4 sm:mt-6 text-sm sm:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Suis tes comptes, catégorise tes dépenses, fixe des budgets mensuels et visualise
                où part vraiment ton argent.
            </p>
            <div class="mt-8 sm:mt-10 px-4 sm:px-0 max-w-xs sm:max-w-none mx-auto">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-block w-full sm:w-auto px-6 py-3 text-sm sm:text-base bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 text-center shadow-sm transition">
                        Accéder à mon tableau de bord
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="inline-block w-full sm:w-auto px-5 py-3 sm:px-6 sm:py-3 text-sm sm:text-base bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 text-center shadow-sm transition">
                        Commencer gratuitement
                    </a>
                @endauth
            </div>
        </section>

        {{-- Features --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-16 sm:pb-24">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">

                <div class="flex flex-col items-center text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6A2.25 2.25 0 0 1 18.75 20H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18-3.75A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25M9 3h6m-3 11.25h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Multi-comptes</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Compte courant, épargne, carte, espèces : centralise tous tes comptes au même endroit.
                    </p>
                </div>

                <div class="flex flex-col items-center text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v5.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 18.375v-5.25ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125v-9.75ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v14.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Budgets par catégorie</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Fixe une limite mensuelle par catégorie et repère instantanément les dépassements.
                    </p>
                </div>

                <div class="flex flex-col items-center text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Transactions récurrentes</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Loyer, abonnements, salaire : automatise le suivi de tes revenus et charges fixes.
                    </p>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 py-6 sm:py-8 px-4 text-center text-xs sm:text-sm text-gray-400 bg-white mt-auto">
        &copy; {{ date('Y') }} NsoloCash — Projet personnel de gestion financière.
    </footer>

    {{-- Gestion JS du Menu Burger --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const burgerBtn = document.getElementById('burger-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const burgerIcon = document.getElementById('burger-icon');
            const closeIcon = document.getElementById('close-icon');

            burgerBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    burgerIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    mobileMenu.classList.add('hidden');
                    burgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>