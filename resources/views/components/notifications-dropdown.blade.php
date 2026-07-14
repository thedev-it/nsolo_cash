@php
    $count = count($notifications);
@endphp

<div class="relative" data-notifications-root>
    <button type="button" data-notifications-toggle
            class="relative text-gray-400 hover:text-gray-600 transition">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>

        @if ($count > 0)
            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-bold">
                {{ $count > 9 ? '9+' : $count }}
            </span>
        @endif
    </button>

    <div data-notifications-panel
         class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl border border-gray-100 shadow-xl z-20 overflow-hidden">

        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-900">Notifications</span>
            @if ($count > 0)
                <span class="text-xs text-gray-400">{{ $count }} active{{ $count > 1 ? 's' : '' }}</span>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse ($notifications as $notification)
                <a href="{{ $notification['url'] }}"
                   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                         style="background-color: {{ $notification['color'] }}1A">
                        @if ($notification['icon'] === 'alert')
                            <svg class="w-4 h-4" style="color: {{ $notification['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" style="color: {{ $notification['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                    <p class="text-sm text-gray-700 leading-snug">{{ $notification['message'] }}</p>
                </a>
            @empty
                <div class="px-4 py-8 text-center text-sm text-gray-400">
                    Tout est calme par ici 🎉<br>Aucune notification pour l'instant.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    (function () {
        const root = document.currentScript.closest('[data-notifications-root]');
        const toggle = root.querySelector('[data-notifications-toggle]');
        const panel = root.querySelector('[data-notifications-panel]');

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!root.contains(e.target)) {
                panel.classList.add('hidden');
            }
        });
    })();
</script>