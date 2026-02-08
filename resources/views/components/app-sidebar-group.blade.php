@props([
    'label' => '',
])

<div x-show="!collapsed" x-transition.opacity class="pt-4 first:pt-0">
    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
        {{ $label }}
    </p>
    <div class="space-y-0.5">
        {{ $slot }}
    </div>
</div>
