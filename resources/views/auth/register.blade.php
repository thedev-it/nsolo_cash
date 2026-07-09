<x-auth-layout
    title="Fais grandir ton argent."
    subtitle="Crée ton compte pour commencer à suivre tes dépenses et atteindre tes objectifs.">

    {{-- Modification ici : text-center pour aligner le titre et le sous-titre au milieu --}}
    <div class="px-2 sm:px-0 text-center">
        <h2 class="font-display text-2xl sm:text-3xl text-[#12103A] mb-1 font-bold">Créer un compte</h2>
        

        {{-- text-left pour garder les labels et inputs alignés proprement à gauche --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-5 text-left">
            @csrf

            {{-- Nom complet --}}
            <div>
                <x-input-label for="name" value="Nom complet" class="text-sm font-medium text-gray-700" />
                <x-text-input id="name" class="block mt-1 w-full text-base sm:text-sm h-11 sm:h-10" type="text" name="name"
                    :value="old('name')" required autofocus autocomplete="name"
                    placeholder="Jean Dupont" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Email --}}
            <div>
                <x-input-label for="email" value="Adresse e-mail" class="text-sm font-medium text-gray-700" />
                <x-text-input id="email" class="block mt-1 w-full text-base sm:text-sm h-11 sm:h-10" type="email" name="email"
                    :value="old('email')" required autocomplete="username"
                    placeholder="toi@exemple.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Mot de passe --}}
            <div>
                <x-input-label for="password" value="Mot de passe" class="text-sm font-medium text-gray-700" />
                <x-text-input id="password" class="block mt-1 w-full text-base sm:text-sm h-11 sm:h-10" type="password" name="password"
                    required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Confirmation Mot de passe --}}
            <div>
                <x-input-label for="password_confirmation" value="Confirmer le mot de passe" class="text-sm font-medium text-gray-700" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full text-base sm:text-sm h-11 sm:h-10" type="password"
                    name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            {{-- Bouton d'action --}}
            <div class="pt-2">
                <button type="submit"
                        class="w-full justify-center inline-flex items-center h-12 sm:h-11 px-4 bg-[#12103A] text-white text-base sm:text-sm font-semibold rounded-md hover:bg-[#1c1857] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition active:scale-[0.98]">
                    Créer mon compte
                </button>
            </div>

            {{-- Lien de redirection --}}
            <p class="text-center text-sm text-gray-500 pt-2">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-emerald-600 no-underline font-semibold hover:underline px-1 py-2">Connecte-toi</a>
            </p>
        </form>
    </div>
</x-auth-layout>