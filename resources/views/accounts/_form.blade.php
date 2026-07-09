@props(['account' => null])

<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Nom du compte</label>
    <input type="text" name="name" id="name"
           value="{{ old('name', $account->name ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           placeholder="Ex : Compte courant">
    @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="type" class="block text-sm font-medium text-gray-700">Type de compte</label>
    <select name="type" id="type"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @foreach (['checking' => 'Compte courant', 'savings' => 'Épargne', 'card' => 'Carte', 'cash' => 'Espèces'] as $value => $label)
            <option value="{{ $value }}" @selected(old('type', $account->type ?? '') === $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="balance" class="block text-sm font-medium text-gray-700">
        {{ $account ? 'Solde actuel' : 'Solde de départ' }}
    </label>
    <input type="number" step="0.01" name="balance" id="balance"
           value="{{ old('balance', $account->balance ?? 0) }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('balance')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @if ($account)
        <p class="mt-1 text-xs text-gray-400">
            ⚠️ Modifier ce champ directement ne recalcule pas l'historique des transactions.
        </p>
    @endif
</div>