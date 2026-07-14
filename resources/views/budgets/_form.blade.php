@props(['budget' => null, 'categories'])

<div class="mb-4">
    <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie de dépense</label>
    <select name="category_id" id="category_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">-- Choisir une catégorie --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $budget->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @if ($categories->isEmpty())
        <p class="mt-1 text-xs text-amber-600">
            Tu n'as aucune catégorie de dépense.<a href="{{ route('categories.create') }}" class="underline">Crée-en une d'abord</a>.
        </p>
    @endif
</div>

<div class="mb-4">
    <label for="month" class="block text-sm font-medium text-gray-700">Mois concerné</label>
    <input type="month" name="month" id="month"
           value="{{ old('month', $budget->month ?? now()->format('Y-m')) }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('month')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="amount_limit" class="block text-sm font-medium text-gray-700">Montant limite</label>
    <input type="number" step="0.01" min="0.01" name="amount_limit" id="amount_limit"
           value="{{ old('amount_limit', $budget->amount_limit ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           placeholder="Ex : 300.00">
    @error('amount_limit')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>