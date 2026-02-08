@props([
    'align' => 'left',
    'width' => 'w-48',
])

@php
    $aligns = [
        'left'  => 'left-0',
        'right' => 'right-0',
    ];
@endphp

<div x-data="{ open: false }" class="relative inline-block">
    <div x-on:click="open = !open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-on:click.away="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $aligns[$align] }} mt-2 {{ $width }} rounded-xl bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 py-1 z-50 overflow-hidden"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</div>
