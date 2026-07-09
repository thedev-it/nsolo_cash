<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier le compte
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('accounts.update', $account) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('accounts._form', ['account' => $account])

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('accounts.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Annuler</a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>