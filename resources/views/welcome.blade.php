<x-layouts.app title="Meu Portfolio">
    <div class="min-h-screen flex flex-col">
        <x-app-navbar brand="Meu Portfolio" variant="glass">
            <x-slot:links>
                <a href="#inicio" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Início</a>
                <a href="#sobre" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Sobre</a>
                <a href="#projetos" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Projetos</a>
                <a href="#contato" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Contato</a>
                <a href="{{ route('admin.projects.index') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Admin</a>
            </x-slot:links>
            <x-app-theme-toggle />
        </x-app-navbar>

        <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12">
            {{-- Hero --}}
            <section id="inicio" class="text-center py-16">
                <x-app-avatar size="xl" initials="JD" class="mx-auto mb-4" />
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Olá, sou [Seu Nome]</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-6">
                    Desenvolvedor / Designer / o que você fizer.
                </p>
                <div class="flex gap-3 justify-center flex-wrap">
                    <x-button variant="primary" size="lg">Contato</x-button>
                    <x-button variant="outline" size="lg">Projetos</x-button>
                </div>
            </section>

            {{-- Cards de destaque (opcional) --}}
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 py-8">
                <x-app-stat-card label="Projetos" value="12" />
                <x-app-stat-card label="Anos de experiência" value="5" variant="glass" />
                <x-app-stat-card label="Clientes" value="8" trend="+2 este mês" trendUp="true" />
            </section>

            {{-- Sobre --}}
            <section id="sobre" class="py-12">
                <x-app-card title="Sobre mim" variant="glass" padding="lg">
                    <p class="text-gray-600 dark:text-gray-400">
                        Aqui você pode colocar um texto sobre você, formação e experiência.
                    </p>
                </x-app-card>
            </section>

            {{-- Projetos (mini CMS) --}}
            <section id="projetos" class="py-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Projetos</h2>
                @if(isset($projects) && $projects->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $project)
                            <x-app-card variant="glass" padding="none" class="overflow-hidden flex flex-col h-full">
                                {{-- Miniatura da página inicial do projeto --}}
                                <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer" class="block aspect-video bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                    @if($project->thumbnail_display_url)
                                        <img
                                            src="{{ $project->thumbnail_display_url }}"
                                            alt="{{ $project->display_name }}"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        />
                                        <div class="w-full h-full hidden items-center justify-center text-gray-400 dark:text-gray-500 bg-gray-200 dark:bg-gray-700" style="min-height: 140px;">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500" style="min-height: 140px;">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        </div>
                                    @endif
                                </a>
                                <div class="p-5 flex-1 flex flex-col">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $project->display_name }}</h3>
                                    @if($project->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm flex-1 line-clamp-4">{{ $project->description }}</p>
                                    @endif
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                            Ver projeto
                                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                        @if($project->github_url ?? null)
                                            <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                                                GitHub
                                                <svg class="w-4 h-4 ml-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </x-app-card>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        <a href="{{ route('admin.projects.index') }}" class="hover:underline">Admin: gerenciar projetos</a>
                    </p>
                @else
                    <x-app-empty-state
                        title="Nenhum projeto publicado"
                        description="Os projetos cadastrados no mini CMS aparecerão aqui."
                    >
                        <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Gerenciar projetos</a>
                    </x-app-empty-state>
                @endif
            </section>
        </main>

        <x-app-footer variant="glass">
            <span>© {{ date('Y') }} Meu Portfolio.</span>
            <div class="flex gap-4">
                <a href="#" class="hover:underline">LinkedIn</a>
                <a href="#" class="hover:underline">GitHub</a>
            </div>
        </x-app-footer>
    </div>
</x-layouts.app>
