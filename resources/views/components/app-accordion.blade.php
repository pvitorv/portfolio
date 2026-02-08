@props([
    'title' => '',
    'open' => false,
])

<div
    x-data="{ expanded: @js($open) }"
    {{ $attributes->merge(['class' => 'border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden']) }}
>
    <button
        x-on:click="expanded = !expanded"
        class="flex items-center justify-between w-full px-5 py-4 text-left bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"
    >
        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $title }}</span>
        <svg
            class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200"
            :class="expanded && 'rotate-180'"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="expanded"
        x-collapse
        class="border-t border-gray-200 dark:border-gray-700"
    >
        <div class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800">
            {{ $slot }}
        </div>
    </div>
</div>
