<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mes comptes
            </h2>
            <a href="{{ route('accounts.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                + Nouveau compte
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($accounts->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        Tu n'as encore aucun compte. Commence par
                        <a href="{{ route('accounts.create') }}" class="text-indigo-600 underline">en créer un</a>.
                    </div>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($accounts as $account)
                            <li class="p-4 flex items-center justify-between hover:bg-gray-50">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $account->name }}</p>
                                    <p class="text-sm text-gray-500 capitalize">{{ $account->type }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-semibold {{ $account->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($account->balance, 2) }} €
                                    </span>
                                    <a href="{{ route('accounts.edit', $account) }}"
                                       class="text-sm text-indigo-600 hover:underline">Modifier</a>
                                    <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce compte et toutes ses transactions ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>