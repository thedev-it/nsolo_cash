<x-auth-layout
    title="Ça arrive à tout le monde."
    subtitle="Indique ton adresse e-mail, on t'envoie un lien pour choisir un nouveau mot de passe.">

    <h2 class="font-display text-2xl text-[#12103A] mb-1">Mot de passe oublié</h2>
    <p class="text-sm text-gray-500 mb-8">
        Tu recevras un lien par e-mail valable un moment limité.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autofocus placeholder="toi@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit"
                class="w-full justify-center inline-flex items-center px-4 py-2.5 bg-[#12103A] text-white text-sm font-semibold rounded-md hover:bg-[#1c1857] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition">
            Envoyer le lien de réinitialisation
        </button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-emerald-600 font-semibold hover:underline">Retour à la connexion</a>
        </p>
    </form>
</x-auth-layout>