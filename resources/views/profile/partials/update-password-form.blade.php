<section>
    <header>
        <h3 class="text-base font-semibold text-gray-900">Changer le mot de passe</h3>
        <p class="mt-1 text-sm text-gray-500">
            Assurez-vous d'utiliser un mot de passe long et unique pour rester en sécurité.
        </p>
    </header>

    <form method="POST" action="{{ route('profile.password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="current_password" value="Mot de passe actuel" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                          autocomplete="current-password" />
            <x-input-error class="mt-2" :messages="$errors->get('current_password')" />
        </div>

        <div>
            <x-input-label for="password" value="Nouveau mot de passe" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                          autocomplete="new-password" />
            <x-input-error class="mt-2" :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le nouveau mot de passe" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full"
                          autocomplete="new-password" />
            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Mettre à jour</x-primary-button>
        </div>
    </form>
</section>