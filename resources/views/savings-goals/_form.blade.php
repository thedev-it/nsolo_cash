@props(['goal' => null])

@php
    $palette = ['#10B981', '#6366F1', '#F59E0B', '#EC4899', '#0EA5E9', '#8B5CF6'];
    $selectedColor = old('color', $goal->color ?? $palette[0]);
@endphp

<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Nom de l'objectif</label>
    <input type="text" name="name" id="name"
           value="{{ old('name', $goal->name ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           placeholder="Ex : Vacances, Fonds d'urgence, Nouvel ordinateur...">
    @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="target_amount" class="block text-sm font-medium text-gray-700">Montant cible (FCFA)</label>
    <input type="number" step="1" min="1" name="target_amount" id="target_amount"
           value="{{ old('target_amount', $goal->target_amount ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           placeholder="Ex : 1000000">
    @error('target_amount')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="target_date" class="block text-sm font-medium text-gray-700">Date cible (optionnel)</label>
    <input type="date" name="target_date" id="target_date"
           value="{{ old('target_date', isset($goal) && $goal->target_date ? $goal->target_date->format('Y-m-d') : '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('target_date')
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

@if (isset($goal))
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Montant déjà épargné</label>
        <input type="text" disabled value="{{ number_format($goal->current_amount, 0, ',', ' ') }} FCFA"
               class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm">
        <p class="mt-1 text-xs text-gray-400">
            Ce montant se met à jour via le bouton "Ajouter" sur la liste des objectifs, pas depuis ce formulaire.
        </p>
    </div>
@endif