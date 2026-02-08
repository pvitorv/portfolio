@props([
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800',
        'glass'   => 'backdrop-blur-md bg-white/70 dark:bg-gray-900/70 border-t border-white/20 dark:border-gray-700/30',
        'dark'    => 'bg-gray-900 text-gray-400 border-t border-gray-800',
    ];
@endphp

<footer {{ $attributes->merge(['class' => 'mt-auto py-6 ' . $variants[$variant]]) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
            {{ $slot }}
        </div>
    </div>
</footer>
