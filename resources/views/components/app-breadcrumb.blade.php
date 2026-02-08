@props([
    'items' => [],
])

<nav {{ $attributes->merge(['class' => 'flex items-center space-x-2 text-sm']) }}>
    @foreach($items as $i => $item)
        @if($i > 0)
            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif

        @if($i === count($items) - 1)
            <span class="text-gray-500 dark:text-gray-400">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] ?? '#' }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $item['label'] }}</a>
        @endif
    @endforeach
</nav>
