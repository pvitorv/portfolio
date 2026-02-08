@props([
    'variant' => 'default',
    'brand' => null,
    'brandIcon' => null,
])

@php
    $variants = [
        'default' => 'bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800',
        'glass'   => 'backdrop-blur-xl bg-white/60 dark:bg-gray-900/60 border-r border-white/20 dark:border-gray-700/30',
        'dark'    => 'bg-gray-950 text-white border-r border-gray-800',
    ];
@endphp

<aside
    x-data="{ collapsed: false, activeGroup: null }"
    :class="collapsed ? 'w-[4.5rem]' : 'w-72'"
    {{ $attributes->merge(['class' => 'h-screen sticky top-0 transition-all duration-300 ease-in-out flex flex-col overflow-hidden select-none ' . $variants[$variant]]) }}
>
    {{-- HEADER / BRAND --}}
    <div class="flex items-center h-16 shrink-0 border-b border-gray-200/60 dark:border-gray-800/60" :class="collapsed ? 'justify-center px-2' : 'gap-3 px-4'">
        <button
            x-on:click="collapsed = !collapsed"
            class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all duration-200 shrink-0"
            :title="collapsed ? 'Expandir' : 'Recolher'"
        >
            <svg class="w-5 h-5 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7"/>
            </svg>
        </button>
        @if($brandIcon)
            <div x-show="!collapsed" x-transition.opacity.duration.200ms class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-md">
                {{ $brandIcon }}
            </div>
        @endif
        <span
            x-show="!collapsed"
            x-transition:enter="transition ease-out duration-200 delay-75"
            x-transition:enter-start="opacity-0 -translate-x-2"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 -translate-x-2"
            class="font-bold text-lg text-gray-900 dark:text-white truncate"
        >
            {{ $brand ?? $slot }}
        </span>
    </div>

    {{-- SEARCH (optional slot) --}}
    @if(isset($search))
        <div class="px-3 pt-3" x-show="!collapsed" x-transition.opacity>
            {{ $search }}
        </div>
    @endif

    {{-- MENU --}}
    @if(isset($menu))
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto overflow-x-hidden scrollbar-thin">
            {{ $menu }}
        </nav>
    @endif

    {{-- FOOTER --}}
    @if(isset($footer))
        <div class="shrink-0 border-t border-gray-200/60 dark:border-gray-800/60 px-3 py-3">
            {{ $footer }}
        </div>
    @endif
</aside>
