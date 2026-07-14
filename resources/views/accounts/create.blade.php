<x-app-layout>
    <x-slot name="header">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouveau compte
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Affichage global des erreurs si nécessaire --}}
            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-md text-sm text-red-700 shadow-sm">
                    <span class="font-semibold">Oups !</span> Veuillez vérifier les erreurs dans le formulaire.
                </div>
            @endif

            <div class="bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden">
                <form action="{{ route('accounts.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    
                    {{-- Contenu du formulaire --}}
                    @include('accounts._form')

                    {{-- Actions du formulaire adaptées aux mobiles (boutons empilés si très petit écran) --}}
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-50">
                        <a href="{{ route('accounts.index') }}"
                           class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200 sm:border-transparent">
                            Annuler
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Créer le compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>