<x-auth-layout
    title="Content de te revoir."
    subtitle="Connecte-toi pour retrouver tes comptes, tes budgets et le détail de tes dépenses.">

    {{-- Modification ici : text-center pour tout aligner au milieu --}}
    <div class="px-2 sm:px-0 text-center">
        <h2 class="font-display text-2xl sm:text-3xl text-[#12103A] mb-1 font-bold">Connexion</h2>
        <p class="text-sm text-gray-500 mb-6 sm:mb-8">Entre tes identifiants pour continuer.</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- Le formulaire reste aligné à gauche en interne pour les labels, ce qui est plus propre --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5 text-left">
            @csrf

            {{-- Email --}}
            <div>
                <x-input-label for="email" value="Adresse e-mail" class="text-sm font-medium text-gray-700" />
                <x-text-input id="email" class="block mt-1 w-full text-base sm:text-sm h-11 sm:h-10" type="email" name="email"
                    :value="old('email')" required autofocus autocomplete="username"
                    placeholder="toi@exemple.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Mot de passe --}}
            <div>
                <x-input-label for="password" value="Mot de passe" class="text-sm font-medium text-gray-700" />
                <x-text-input id="password" class="block mt-1 w-full text-base sm:text-sm h-11 sm:h-10" type="password" name="password"
                    required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Options : Se souvenir de moi & Mot de passe oublié --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between pt-1">
                <label for="remember_me" class="flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox"
                           class="h-4 w-4 rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                           name="remember">
                    <span class="ms-2.5 text-sm text-gray-600">Se souvenir de moi</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-emerald-600 hover:text-emerald-700 hover:underline" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            {{-- Bouton de soumission --}}
            <div class="pt-2">
                <button type="submit"
                        class="w-full justify-center inline-flex items-center h-12 sm:h-11 px-4 bg-[#12103A] text-white text-base sm:text-sm font-semibold rounded-md hover:bg-[#1c1857] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition active:scale-[0.98]">
                    Se connecter
                </button>
            </div>

            {{-- Lien d'inscription --}}
            <p class="text-center text-sm text-gray-500 pt-2">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-emerald-600 font-semibold hover:underline px-1 py-2">Inscris-toi</a>
            </p>
        </form>
    </div>
</x-auth-layout>