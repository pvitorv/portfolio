@props([
    'title' => null,
    'blur' => 'md',
])

@php
    $blurs = [
        'sm' => 'backdrop-blur-sm bg-white/40 dark:bg-gray-800/40',
        'md' => 'backdrop-blur-md bg-white/50 dark:bg-gray-800/50',
        'lg' => 'backdrop-blur-lg bg-white/60 dark:bg-gray-800/60',
        'xl' => 'backdrop-blur-xl bg-white/70 dark:bg-gray-800/70',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl shadow-lg border border-white/30 dark:border-gray-700/30 p-6 ' . $blurs[$blur]]) }}>
    @if($title)
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
