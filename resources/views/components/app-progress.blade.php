@props([
    'value' => 0,
    'max' => 100,
    'size' => 'md',
    'color' => 'blue',
    'showLabel' => false,
])

@php
    $percent = min(100, max(0, ($value / $max) * 100));

    $sizes = [
        'sm' => 'h-1.5',
        'md' => 'h-2.5',
        'lg' => 'h-4',
    ];

    $colors = [
        'blue'    => 'bg-blue-600 dark:bg-blue-500',
        'green'   => 'bg-emerald-600 dark:bg-emerald-500',
        'red'     => 'bg-red-600 dark:bg-red-500',
        'amber'   => 'bg-amber-500 dark:bg-amber-400',
        'purple'  => 'bg-purple-600 dark:bg-purple-500',
    ];
@endphp

<div {{ $attributes }}>
    @if($showLabel)
        <div class="flex justify-between mb-1 text-sm">
            <span class="text-gray-700 dark:text-gray-300">{{ $slot }}</span>
            <span class="text-gray-500 dark:text-gray-400">{{ round($percent) }}%</span>
        </div>
    @endif
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden {{ $sizes[$size] }}">
        <div
            class="h-full rounded-full transition-all duration-500 ease-out {{ $colors[$color] }}"
            style="width: {{ $percent }}%"
        ></div>
    </div>
</div>
