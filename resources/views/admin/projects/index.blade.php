<x-layouts.app title="Projetos – Admin">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <x-app-navbar brand="Mini CMS" variant="default">
            <x-slot:links>
                <a href="{{ url('/') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Ver site</a>
            </x-slot:links>
            <x-app-theme-toggle />
        </x-app-navbar>

        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <x-app-alert variant="success" class="mb-6">{{ session('success') }}</x-app-alert>
            @endif

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Projetos</h1>
                <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">Novo projeto</a>
            </div>

            @if($projects->isEmpty())
                <x-app-empty-state
                    title="Nenhum projeto ainda"
                    description="Crie seu primeiro projeto para exibir no portfolio."
                >
                    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Criar projeto</a>
                </x-app-empty-state>
            @else
                <div class="space-y-4">
                    @foreach($projects as $project)
                        <x-app-card variant="default" padding="md" class="flex flex-col sm:flex-row sm:items-center gap-4">
                            @if($project->thumbnail_display_url)
                                <img src="{{ $project->thumbnail_display_url }}" alt="" class="w-24 h-16 object-cover rounded-lg shrink-0" />
                            @else
                                <div class="w-24 h-16 rounded-lg bg-gray-200 dark:bg-gray-700 shrink-0 flex items-center justify-center text-gray-400 text-xs">Sem imagem</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h2 class="font-semibold text-gray-900 dark:text-white">{{ $project->title }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $project->url }}</p>
                                @if(!$project->is_visible)
                                    <x-app-badge variant="warning" size="sm" class="mt-1">Oculto</x-app-badge>
                                @endif
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Editar</a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Remover este projeto?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger" size="sm">Excluir</x-button>
                                </form>
                            </div>
                        </x-app-card>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</x-layouts.app>
