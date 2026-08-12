<x-layouts.admin title="Links – Admin">
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <x-app-alert variant="success" class="mb-6">{{ session('success') }}</x-app-alert>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hub de links</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Página pública: <a href="{{ route('links.index') }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">{{ route('links.index') }}</a>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Projetos e redes do perfil entram automaticamente na página.
                </p>
            </div>
            <a href="{{ route('admin.bio-links.create') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Novo link extra</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <x-app-stat-card label="Total de cliques (tudo)" :value="number_format($totalClicks, 0, ',', '.')" />
            <x-app-stat-card label="Cliques (últimos 7 dias)" :value="number_format($clicksLast7Days, 0, ',', '.')" />
        </div>

        @if($portfolioSections->isNotEmpty())
            <div class="mb-10">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Do portfólio (automático)</h2>
                <div class="space-y-6">
                    @foreach($portfolioSections as $section)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">{{ $section['label'] }}</h3>
                            <div class="space-y-3">
                                @foreach($section['items'] as $item)
                                    <x-app-card variant="default" padding="md" class="flex flex-col sm:flex-row sm:items-center gap-3">
                                        <div class="flex items-start gap-3 flex-1 min-w-0">
                                            @if($item->thumbnailUrl)
                                                <img src="{{ $item->thumbnailUrl }}" alt="" class="w-14 h-10 rounded object-cover shrink-0" />
                                            @endif
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $item->title }}</span>
                                                    <x-app-badge variant="primary" size="sm">{{ strtoupper($item->source) }}</x-app-badge>
                                                </div>
                                                @if($item->description)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $item->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="{{ $item->href }}" target="_blank" class="shrink-0 inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Testar</a>
                                    </x-app-card>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Edite em <a href="{{ route('admin.projects.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Projetos</a> e
                    <a href="{{ route('admin.profile.edit') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Perfil</a>.
                </p>
            </div>
        @endif

        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Links extras (Instagram, etc.)</h2>

            @if($links->isEmpty())
                <x-app-empty-state
                    title="Nenhum link extra"
                    description="Opcional: links que não estão no portfólio (Instagram, loja, etc.)."
                >
                    <a href="{{ route('admin.bio-links.create') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700">Criar link extra</a>
                </x-app-empty-state>
            @else
                <div class="space-y-4">
                    @foreach($links as $link)
                        <x-app-card variant="default" padding="md" class="flex flex-col lg:flex-row lg:items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $link->title }}</h3>
                                    <x-app-badge variant="default" size="sm">{{ strtoupper($link->type) }}</x-app-badge>
                                    @if(!$link->is_active)
                                        <x-app-badge variant="warning" size="sm">Inativo</x-app-badge>
                                    @endif
                                </div>
                                @if($link->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">{{ $link->description }}</p>
                                @endif
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $link->url }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    {{ number_format($link->click_count, 0, ',', '.') }} cliques
                                    @if($link->last_clicked_at)
                                        · último {{ $link->last_clicked_at->diffForHumans() }}
                                    @endif
                                    · {{ $link->clicksLastDays(7) }} nos últimos 7 dias
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2 shrink-0">
                                <a href="{{ route('links.go', $link) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Testar</a>
                                <a href="{{ route('admin.bio-links.edit', $link) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Editar</a>
                                <form action="{{ route('admin.bio-links.reset-stats', $link) }}" method="POST" onsubmit="return confirm('Zerar estatísticas deste link?');">
                                    @csrf
                                    <x-button type="submit" variant="secondary" size="sm">Zerar stats</x-button>
                                </form>
                                <form action="{{ route('admin.bio-links.destroy', $link) }}" method="POST" onsubmit="return confirm('Remover este link?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger" size="sm">Excluir</x-button>
                                </form>
                            </div>
                        </x-app-card>
                    @endforeach
                </div>
            @endif
        </div>

        <p class="mt-8 text-sm text-gray-500 dark:text-gray-400">
            Foto, nome e bio vêm do <a href="{{ route('admin.profile.edit') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Perfil</a>.
        </p>
    </main>
</x-layouts.admin>
