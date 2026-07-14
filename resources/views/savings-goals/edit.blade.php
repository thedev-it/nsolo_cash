<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier l'objectif</h2>
    </x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-white p-6 rounded-xl border border-gray-100">
            <form action="{{ route('saving-goals.update', $goal) }}" method="POST">
                @csrf
                @method('PUT')
                @include('savings-goals._form', ['goal' => $goal])

                <div class="flex justify-end gap-3">
                    <a href="{{ route('saving-goals.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>