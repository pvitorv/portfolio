<x-layouts.app :title="$title ?? 'Admin'" :noindex="true">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <x-app-navbar brand="Admin" variant="default">
            <x-slot:links>
                <a href="{{ route('admin.projects.index') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Projetos</a>
                <a href="{{ route('admin.profile.edit') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Perfil</a>
                <a href="{{ url('/') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Ver site</a>
            </x-slot:links>
            <div class="flex items-center gap-2">
                <x-app-theme-toggle />
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-black/5 dark:hover:bg-white/5">Sair</button>
                </form>
            </div>
        </x-app-navbar>
        {{ $slot }}
    </div>
</x-layouts.app>
