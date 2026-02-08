@props([
    'label' => null,
])

<label class="inline-flex items-center gap-2 cursor-pointer group">
    <input
        type="checkbox"
        {{ $attributes->merge([
            'class' => 'w-4.5 h-4.5 rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 bg-white dark:bg-gray-800 focus:ring-blue-500 focus:ring-2 focus:ring-offset-0 transition-colors cursor-pointer'
        ]) }}
    />
    @if($label)
        <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100 select-none">{{ $label }}</span>
    @endif
</label>
