@props([
    'variant' => 'info',
    'position' => 'top-right',
])

@php
    $positions = [
        'top-right'    => 'top-4 right-4',
        'top-left'     => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left'  => 'bottom-4 left-4',
        'top-center'   => 'top-4 left-1/2 -translate-x-1/2',
    ];

    $variants = [
        'info'    => 'bg-white dark:bg-gray-800 border-l-4 border-l-blue-500',
        'success' => 'bg-white dark:bg-gray-800 border-l-4 border-l-emerald-500',
        'warning' => 'bg-white dark:bg-gray-800 border-l-4 border-l-amber-500',
        'danger'  => 'bg-white dark:bg-gray-800 border-l-4 border-l-red-500',
    ];
@endphp

<div
    x-data="{ show: false }"
    x-on:toast-{{ $variant }}.window="show = true; setTimeout(() => show = false, 4000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed z-50 {{ $positions[$position] }}"
    style="display: none;"
>
    <div {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 min-w-72 ' . $variants[$variant]]) }}>
        <div class="flex-1 text-sm text-gray-800 dark:text-gray-200">{{ $slot }}</div>
        <button x-on:click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
