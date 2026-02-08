@props([
    'label' => null,
    'checked' => false,
])

<label class="inline-flex items-center gap-3 cursor-pointer group">
    <button
        type="button"
        role="switch"
        x-data="{ on: @js($checked) }"
        x-on:click="on = !on"
        :aria-checked="on"
        :class="on ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-300 dark:bg-gray-600'"
        {{ $attributes->merge(['class' => 'relative w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900']) }}
    >
        <span
            :class="on ? 'translate-x-5' : 'translate-x-0.5'"
            class="inline-block w-5 h-5 bg-white rounded-full shadow transform transition-transform duration-200 mt-0.5"
        ></span>
    </button>
    @if($label)
        <span class="text-sm text-gray-700 dark:text-gray-300 select-none">{{ $label }}</span>
    @endif
</label>
