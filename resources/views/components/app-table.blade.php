@props([
    'striped' => false,
])

<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700']) }}>
    <table class="w-full text-sm text-left">
        @if(isset($head))
            <thead class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-800/50">
                {{ $head }}
            </thead>
        @endif
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800
            {{ $striped ? '[&>tr:nth-child(even)]:bg-gray-50 dark:[&>tr:nth-child(even)]:bg-gray-800/50' : '' }}">
            {{ $slot }}
        </tbody>
    </table>
</div>
