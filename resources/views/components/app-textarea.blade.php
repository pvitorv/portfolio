@props([
    'label' => null,
    'error' => null,
    'hint' => null,
])

<div>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
    @endif

    <textarea {{ $attributes->merge([
        'rows' => 4,
        'class' => 'w-full px-4 py-2.5 rounded-lg border transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0 resize-y '
            . ($error
                ? 'border-red-400 dark:border-red-500 focus:ring-red-500 bg-red-50 dark:bg-red-900/20'
                : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500 bg-white dark:bg-gray-800')
            . ' text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500'
    ]) }}>{{ $slot }}</textarea>

    @if($hint && !$error)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
