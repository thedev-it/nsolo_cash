<x-app-layout>
    <x-slot name="header">
        <div class="px-4 sm:px-0">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mon profil</h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-6">

        {{-- Informations du profil --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Modifier le mot de passe --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Supprimer le compte --}}
        <div class="bg-white rounded-2xl border border-red-100 p-4 sm:p-6 shadow-sm">
            @include('profile.partials.delete-account-form')
        </div>

    </div>
</x-app-layout>