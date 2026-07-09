<x-auth-layout
    title="Choisis un nouveau mot de passe."
    subtitle="Dernière étape avant de retrouver l'accès à ton compte.">

    <h2 class="font-display text-2xl text-[#12103A] mb-1">Nouveau mot de passe</h2>
    <p class="text-sm text-gray-500 mb-8">Choisis un mot de passe que tu n'utilises nulle part ailleurs.</p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email', $email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Nouveau mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
                class="w-full justify-center inline-flex items-center px-4 py-2.5 bg-[#12103A] text-white text-sm font-semibold rounded-md hover:bg-[#1c1857] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition">
            Réinitialiser le mot de passe
        </button>
    </form>
</x-auth-layout>