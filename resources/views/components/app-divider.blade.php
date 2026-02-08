@props([
    'text' => null,
])

@if($text)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-4']) }}>
        <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
        <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $text }}</span>
        <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
    </div>
@else
    <hr {{ $attributes->merge(['class' => 'border-gray-200 dark:border-gray-700']) }} />
@endif
