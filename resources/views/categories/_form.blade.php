@props(['category' => null])

@php
    $palette = ['#6366F1', '#10B981', '#EF4444', '#F59E0B', '#0EA5E9', '#EC4899', '#8B5CF6', '#64748B'];
    $selectedColor = old('color', $category->color ?? $palette[0]);
@endphp

<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Nom de la catégorie</label>
    <input type="text" name="name" id="name"
           value="{{ old('name', $category->name ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           placeholder="Ex : Alimentation, Salaire...">
    @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
    <div class="flex gap-3">
        @foreach (['expense' => 'Dépense', 'income' => 'Revenu'] as $value => $label)
            <label class="flex-1">
                <input type="radio" name="type" value="{{ $value }}" class="peer sr-only"
                       @checked(old('type', $category->type ?? 'expense') === $value)>
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

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
    <div class="flex flex-wrap gap-3">
        @foreach ($palette as $color)
            <label>
                <input type="radio" name="color" value="{{ $color }}" class="peer sr-only"
                       @checked($selectedColor === $color)>
                <span class="block w-8 h-8 rounded-full cursor-pointer ring-offset-2 peer-checked:ring-2 peer-checked:ring-gray-900"
                      style="background-color: {{ $color }}"></span>
            </label>
        @endforeach
    </div>
    @error('color')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>