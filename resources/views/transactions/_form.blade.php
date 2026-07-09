@props(['transaction' => null, 'accounts', 'categories'])

@php
    $selectedType = old('type', $transaction->type ?? 'expense');
@endphp

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
    <div class="flex gap-3">
        @foreach (['expense' => 'Dépense', 'income' => 'Revenu'] as $value => $label)
            <label class="flex-1">
                <input type="radio" name="type" value="{{ $value }}" class="peer sr-only" data-type-radio
                       @checked($selectedType === $value)>
                <div class="text-center px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-600 cursor-pointer
                            peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-600">
                    {{ $label }}
                </div>
            </label>
        @endforeach
    </div>
    @error('type')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="account_id" class="block text-sm font-medium text-gray-700">Compte</label>
    <select name="account_id" id="account_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">-- Choisir un compte --</option>
        @foreach ($accounts as $account)
            <option value="{{ $account->id }}" @selected(old('account_id', $transaction->account_id ?? '') == $account->id)>
                {{ $account->name }} ({{ number_format($account->balance, 2) }} €)
            </option>
        @endforeach
    </select>
    @error('account_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @if ($accounts->isEmpty())
        <p class="mt-1 text-xs text-amber-600">
            Tu n'as aucun compte. <a href="{{ route('accounts.create') }}" class="underline">Crée-en un d'abord</a>.
        </p>
    @endif
</div>

<div class="mb-4">
    <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
    <select name="category_id" id="category_id" data-category-select
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">-- Choisir une catégorie --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" data-type="{{ $category->type }}"
                    @selected(old('category_id', $transaction->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @if ($categories->isEmpty())
        <p class="mt-1 text-xs text-amber-600">
            Tu n'as aucune catégorie. <a href="{{ route('categories.create') }}" class="underline">Crée-en une d'abord</a>.
        </p>
    @endif
</div>

<div class="mb-4">
    <label for="amount" class="block text-sm font-medium text-gray-700">Montant</label>
    <input type="number" step="0.01" min="0.01" name="amount" id="amount"
           value="{{ old('amount', $transaction->amount ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           placeholder="0.00">
    @error('amount')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
    <input type="date" name="date" id="date"
           value="{{ old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d')) }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('date')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="description" class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
    <input type="text" name="description" id="description"
           value="{{ old('description', $transaction->description ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           placeholder="Ex : Courses chez Carrefour">
    @error('description')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
    // Filtre les options de catégorie selon le type sélectionné (dépense/revenu)
    (function () {
        const typeRadios = document.querySelectorAll('[data-type-radio]');
        const categorySelect = document.querySelector('[data-category-select]');

        function filterCategories() {
            const selectedType = document.querySelector('[data-type-radio]:checked')?.value;

            [...categorySelect.options].forEach((option) => {
                if (!option.value) return; // garde toujours l'option vide

                const matches = option.dataset.type === selectedType;
                option.hidden = !matches;

                if (!matches && option.selected) {
                    categorySelect.value = '';
                }
            });
        }

        typeRadios.forEach((radio) => radio.addEventListener('change', filterCategories));
        filterCategories();
    })();
</script>