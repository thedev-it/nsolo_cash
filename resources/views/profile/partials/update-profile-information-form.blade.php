<section>
    <header>
        <h3 class="text-base font-semibold text-gray-900">Informations du profil</h3>
        <p class="mt-1 text-sm text-gray-500">
            Mettez à jour votre nom et votre adresse e-mail.
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <x-input-label for="name" value="Nom complet" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Enregistrer</x-primary-button>

            @if (session('success'))
                <p class="text-sm text-emerald-600">Enregistré.</p>
            @endif
        </div>
    </form>
</section>