<x-layouts.admin title="Links – Admin">
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <x-app-alert variant="success" class="mb-6">{{ session('success') }}</x-app-alert>
        @endif

        @if($dashboard['migration_pending'] ?? false)
            <x-app-alert variant="warning" class="mb-6">
                Analytics incompleto: rode <code class="text-xs bg-black/10 dark:bg-white/10 px-1 rounded">php artisan migrate --force</code> no servidor para ativar impressões e visitas.
            </x-app-alert>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hub de links</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Página pública:
                    <a href="{{ route('links.index') }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">{{ route('links.index') }}</a>
                </p>
            </div>
            <a href="{{ route('admin.bio-links.create') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 shrink-0">Novo link extra</a>
        </div>

        {{-- resumo geral --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <x-app-stat-card label="Visitas à página" :value="number_format($dashboard['page_views'], 0, ',', '.')" />
            <x-app-stat-card label="Visitas (7 dias)" :value="number_format($dashboard['page_views_7d'], 0, ',', '.')" />
            <x-app-stat-card label="Impressões de links" :value="number_format($dashboard['total_impressions'], 0, ',', '.')" />
            <x-app-stat-card label="Cliques totais" :value="number_format($dashboard['total_clicks'], 0, ',', '.')" />
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
            <strong>Impressão</strong> = cada vez que o link apareceu em uma visita à página.
            <strong>CTR</strong> = cliques ÷ impressões.
        </p>

        {{-- tabela analítica --}}
        <x-app-card variant="default" padding="none" class="mb-10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Link</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hidden sm:table-cell">Origem</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Impressões</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-600 dark:text-gray-400">7d imp.</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Cliques</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-600 dark:text-gray-400">7d clk.</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-600 dark:text-gray-400">CTR</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hidden lg:table-cell">Última atividade</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($dashboard['items'] as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $row['title'] }}</div>
                                    @if($row['description'])
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $row['description'] }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    <x-app-badge variant="default" size="sm">{{ $row['source_label'] }}</x-app-badge>
                                </td>
                                <td class="px-4 py-3 text-right tabular-nums text-gray-900 dark:text-white">{{ number_format($row['impressions'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($row['impressions_7d'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-gray-900 dark:text-white">{{ number_format($row['clicks'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($row['clicks_7d'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right tabular-nums">
                                    <span class="{{ $row['ctr'] >= 10 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-600 dark:text-gray-400' }}">{{ $row['ctr'] }}%</span>
                                </td>
                                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500 dark:text-gray-400">
                                    @if($row['last_click'])
                                        Clique {{ $row['last_click']->diffForHumans() }}
                                    @elseif($row['last_impression'])
                                        Visto {{ $row['last_impression']->diffForHumans() }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ $row['href'] }}" target="_blank" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" title="Testar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                        @if($row['editable'] && $row['bio_link_id'])
                                            <a href="{{ route('admin.bio-links.edit', $row['bio_link_id']) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.bio-links.reset-item-stats') }}" method="POST" onsubmit="return confirm('Zerar estatísticas deste link?');">
                                            @csrf
                                            <input type="hidden" name="source_type" value="{{ $row['source'] }}">
                                            <input type="hidden" name="source_key" value="{{ $row['source_key'] }}">
                                            <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-800" title="Zerar stats">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Nenhum link na página ainda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-app-card>

        {{-- gerenciar links extras --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Links extras</h2>

            @if($links->isEmpty())
                <x-app-empty-state
                    title="Nenhum link extra"
                    description="Links manuais (Instagram, loja…) além do que vem do portfólio."
                >
                    <a href="{{ route('admin.bio-links.create') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700">Criar link extra</a>
                </x-app-empty-state>
            @else
                <div class="space-y-3">
                    @foreach($links as $link)
                        <x-app-card variant="default" padding="md" class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $link->title }}</span>
                                    @if(!$link->is_active)
                                        <x-app-badge variant="warning" size="sm">Inativo</x-app-badge>
                                    @endif
                                </div>
                                @if($link->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $link->description }}</p>
                                @endif
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <a href="{{ route('admin.bio-links.edit', $link) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Editar</a>
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
    </main>
</x-layouts.admin>
