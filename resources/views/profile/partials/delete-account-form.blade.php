<section>
    <header>
        <h3 class="text-base font-semibold text-red-600">Supprimer le compte</h3>
        <p class="mt-1 text-sm text-gray-500">
            Une fois votre compte supprimé, toutes ses données (comptes, transactions, budgets, objectifs) seront
            définitivement effacées. Téléchargez vos rapports si besoin avant de continuer.
        </p>
    </header>

    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6 space-y-4"
          data-confirm-delete
          data-confirm-text="Cette action est irréversible. Toutes vos données seront perdues. Continuer ?">
        @csrf
        @method('DELETE')

        <div>
            <x-input-label for="password" value="Mot de passe" class="sr-only" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full sm:w-1/2"
                          placeholder="Confirmez avec votre mot de passe" />
            <x-input-error class="mt-2" :messages="$errors->get('password', 'userDeletion')" />
        </div>

        <x-danger-button>Supprimer définitivement mon compte</x-danger-button>
    </form>
</section>