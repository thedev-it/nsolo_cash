<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes transactions</h2>
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700">
                + Nouvelle transaction
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        @if (session('success'))
            <div class="p-4 bg-emerald-100 text-emerald-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            @if ($transactions->isEmpty())
                <div class="p-10 text-center text-sm text-gray-500">
                    Aucune transaction pour l'instant.
                    <a href="{{ route('transactions.create') }}" class="text-indigo-600 underline">
                        Ajoute ta première transaction
                    </a>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs text-gray-400 uppercase tracking-wide">
                            <th class="px-6 py-3 font-medium">Détails</th>
                            <th class="px-6 py-3 font-medium">Compte</th>
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium text-right">Montant</th>
                            <th class="px-6 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $transaction->category->color }}"></span>
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $transaction->description ?: $transaction->category->name }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $transaction->category->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $transaction->account->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $transaction->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right font-semibold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} €
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="text-sm text-indigo-600 hover:underline">Modifier</a>
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                              onsubmit="return confirm('Supprimer cette transaction ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:underline">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>