@props([
    'title' => null,
    'variant' => 'default',
    'padding' => 'md',
])

@php
    $variants = [
        'default' => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
        'glass'   => 'backdrop-blur-md bg-white/60 dark:bg-gray-800/60 border border-white/20 dark:border-gray-700/30',
        'flat'    => 'bg-gray-50 dark:bg-gray-800/50',
        'outline' => 'bg-transparent border border-gray-300 dark:border-gray-600',
    ];

    $paddings = [
        'none' => 'p-0',
        'sm'   => 'p-4',
        'md'   => 'p-6',
        'lg'   => 'p-8',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl shadow-lg ' . $variants[$variant] . ' ' . $paddings[$padding]]) }}>
    @if($title)
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
