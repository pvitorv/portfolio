@props([
    'src' => null,
    'alt' => '',
    'size' => 'md',
    'initials' => null,
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-14 h-14 text-lg',
        'xl' => 'w-20 h-20 text-2xl',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden shrink-0 ' . $sizes[$size]]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $alt }}" class="w-full h-full object-cover" />
    @elseif($initials)
        <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $initials }}</span>
    @else
        <svg class="w-1/2 h-1/2 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
    @endif
</div>
