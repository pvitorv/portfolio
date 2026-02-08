@props([
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $variants = [
        'primary'   => 'bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:ring-blue-500',
        'secondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 focus:ring-gray-400',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 focus:ring-red-500',
        'success'   => 'bg-emerald-600 text-white hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 focus:ring-emerald-500',
        'warning'   => 'bg-amber-500 text-white hover:bg-amber-600 dark:bg-amber-400 dark:text-gray-900 dark:hover:bg-amber-500 focus:ring-amber-500',
        'ghost'     => 'bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 focus:ring-gray-400',
        'outline'   => 'bg-transparent border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 focus:ring-gray-400',
        'glass'     => 'backdrop-blur-sm bg-white/20 text-white border border-white/30 hover:bg-white/30 focus:ring-white/50',
    ];

    $sizes = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        'xl' => 'px-6 py-3 text-lg',
    ];

    $base = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer';
@endphp

<button {{ $attributes->merge(['class' => $base . ' ' . $variants[$variant] . ' ' . $sizes[$size]]) }}>
    {{ $slot }}
</button>
