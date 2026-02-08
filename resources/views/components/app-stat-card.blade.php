@props([
    'label' => '',
    'value' => '0',
    'icon' => null,
    'trend' => null,
    'trendUp' => true,
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
        'glass'   => 'backdrop-blur-md bg-white/50 dark:bg-gray-800/50 border border-white/20 dark:border-gray-700/30',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl shadow-lg p-6 ' . $variants[$variant]]) }}>
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
        @if($icon)
            <span class="text-gray-400 dark:text-gray-500">{!! $icon !!}</span>
        @endif
    </div>
    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</div>
    @if($trend)
        <div class="flex items-center mt-2 text-sm {{ $trendUp ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
            <svg class="w-4 h-4 mr-1 {{ $trendUp ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            </svg>
            {{ $trend }}
        </div>
    @endif
</div>
