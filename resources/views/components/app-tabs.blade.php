@props([
    'tabs' => [],
    'active' => null,
])

@php
    $activeTab = $active ?? (count($tabs) > 0 ? array_key_first($tabs) : null);
@endphp

<div x-data="{ tab: '{{ $activeTab }}' }" {{ $attributes }}>
    <div class="flex border-b border-gray-200 dark:border-gray-700 gap-1">
        @foreach($tabs as $key => $label)
            <button
                x-on:click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}'
                    ? 'border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400'
                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors duration-200"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
