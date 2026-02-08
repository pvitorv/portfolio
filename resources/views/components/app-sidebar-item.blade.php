@props([
    'icon' => null,
    'active' => false,
    'badge' => null,
    'href' => '#',
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 '
            . ($active
                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 shadow-sm'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200')
    ]) }}
>
    @if($icon)
        <span class="w-5 h-5 shrink-0 flex items-center justify-center {{ $active ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-400' }} transition-colors">
            {!! $icon !!}
        </span>
    @endif

    <span x-show="!collapsed" x-transition.opacity.duration.200ms class="truncate">{{ $slot }}</span>

    @if($badge)
        <span
            x-show="!collapsed"
            x-transition.opacity
            class="ml-auto inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-semibold rounded-full
            {{ $active
                ? 'bg-blue-100 dark:bg-blue-800/40 text-blue-700 dark:text-blue-300'
                : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}"
        >
            {{ $badge }}
        </span>
    @endif
</a>
