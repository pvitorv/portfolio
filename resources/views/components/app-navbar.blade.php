@props([
    'brand' => 'App',
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800',
        'glass'   => 'backdrop-blur-md bg-white/70 dark:bg-gray-900/70 border-b border-white/20 dark:border-gray-700/30',
        'dark'    => 'bg-gray-900 text-white border-b border-gray-800',
    ];
@endphp

<nav {{ $attributes->merge(['class' => 'sticky top-0 z-40 ' . $variants[$variant]]) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="/" class="text-xl font-bold text-gray-900 dark:text-white">{{ $brand }}</a>
                @if(isset($links))
                    <div class="hidden md:flex items-center gap-1">
                        {{ $links }}
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-3">
                {{ $slot }}
            </div>
        </div>
    </div>
</nav>
