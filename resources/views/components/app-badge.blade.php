@props([
    'variant' => 'default',
    'size' => 'md',
    'rounded' => false,
])

@php
    $variants = [
        'default' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
        'primary' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
        'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
        'danger'  => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1 text-sm',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center font-medium '
        . ($rounded ? 'rounded-full' : 'rounded-md')
        . ' ' . $variants[$variant]
        . ' ' . $sizes[$size]
]) }}>
    {{ $slot }}
</span>
