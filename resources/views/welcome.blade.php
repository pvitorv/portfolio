<x-layouts.app title="Meu Portfolio">
    <div class="min-h-screen flex flex-col">
        <x-app-navbar brand="Meu Portfolio" variant="glass">
            <x-slot:links>
                <a href="#inicio" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Início</a>
                <a href="#sobre" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Sobre</a>
                <a href="#projetos" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Projetos</a>
                <a href="#contato" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Contato</a>
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Entrar</a>
            </x-slot:links>
            <x-app-theme-toggle />
        </x-app-navbar>

        <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12">
            {{-- Hero (dados do perfil) --}}
            <section id="inicio" class="text-center py-16">
                @if(isset($profile) && $profile->photo_display_url)
                    <img src="{{ $profile->photo_display_url }}" alt="{{ $profile->name }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-2 border-gray-200 dark:border-gray-700" />
                @else
                    @php
                        $initials = isset($profile) && $profile->name ? implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', $profile->name), 0, 2))) : '?';
                    @endphp
                    <x-app-avatar size="xl" :initials="$initials" class="mx-auto mb-4" />
                @endif
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Olá, sou {{ isset($profile) ? $profile->name : 'Seu Nome' }}</h1>
                @if(isset($profile) && $profile->title)
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-6">{{ $profile->title }}</p>
                @else
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-6">Desenvolvedor / Designer</p>
                @endif
                <div class="flex gap-3 justify-center flex-wrap">
                    <a href="#projetos" class="inline-flex items-center justify-center font-medium rounded-lg px-5 py-2.5 text-base bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Ver projetos</a>
                    <a href="#sobre" class="inline-flex items-center justify-center font-medium rounded-lg px-5 py-2.5 text-base border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Sobre mim</a>
                </div>
            </section>

            {{-- Cards de destaque --}}
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 py-8">
                <x-app-stat-card label="Projetos" :value="isset($projects) ? (string) $projects->count() : '0'" />
            </section>

            {{-- Sobre (currículo / bio do perfil) --}}
            <section id="sobre" class="py-12">
                <x-app-card title="Sobre mim" variant="glass" padding="lg">
                    @if(isset($profile) && $profile->bio)
                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $profile->bio }}</p>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Edite seu perfil no admin para exibir seu currículo e dados aqui.</p>
                    @endif
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
                                <div class="p-5 flex-1 flex flex-col" x-data="{ expanded: false }">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $project->display_name }}</h3>
                                    @if($project->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm flex-1 transition-all"
                                           :class="expanded ? '' : 'line-clamp-4'"
                                        >{{ $project->description }}</p>
                                        <div class="flex gap-2 mt-1" x-cloak>
                                            <button type="button"
                                                    @click="expanded = true"
                                                    x-show="!expanded"
                                                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline focus:outline-none"
                                            >Ler mais</button>
                                            <button type="button"
                                                    @click="expanded = false"
                                                    x-show="expanded"
                                                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline focus:outline-none"
                                            >Ler menos</button>
                                        </div>
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
                        <a href="{{ route('login') }}" class="hover:underline">Entrar para gerenciar</a>
                    </p>
                @else
                    <x-app-empty-state
                        title="Nenhum projeto publicado"
                        description="Os projetos cadastrados no mini CMS aparecerão aqui."
                    >
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center font-medium rounded-lg px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Entrar</a>
                    </x-app-empty-state>
                @endif
            </section>
        </main>

        {{-- Seção Contato (no rodapé, bem estruturada) --}}
        <footer id="contato" class="mt-auto">
            <section class="backdrop-blur-md bg-white/70 dark:bg-gray-900/70 border-t border-white/20 dark:border-gray-700/30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Contato</h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
                        {{-- Dados de contato (perfil) --}}
                        <div class="space-y-6">
                            <p class="text-gray-600 dark:text-gray-400">Entre em contato por e-mail, redes ou use o formulário ao lado.</p>
                            <dl class="space-y-3 text-sm">
                                @if(isset($profile) && $profile->email)
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                                        <dd><a href="mailto:{{ $profile->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $profile->email }}</a></dd>
                                    </div>
                                @endif
                                @if(isset($profile) && $profile->phone)
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                                        <dd><a href="tel:{{ preg_replace('/\s+/', '', $profile->phone) }}" class="text-gray-900 dark:text-white hover:underline">{{ $profile->phone }}</a></dd>
                                    </div>
                                @endif
                                @if(isset($profile) && $profile->location)
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Localização</dt>
                                        <dd class="text-gray-900 dark:text-white">{{ $profile->location }}</dd>
                                    </div>
                                @endif
                            </dl>
                            <div class="flex flex-wrap gap-4 pt-2">
                                @if(isset($profile) && $profile->linkedin_url)
                                    <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                        LinkedIn
                                    </a>
                                @endif
                                @if(isset($profile) && $profile->github_url)
                                    <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                        GitHub
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Formulário de contato --}}
                        <div>
                            @if(session('success'))
                                <x-app-alert variant="success" class="mb-4">{{ session('success') }}</x-app-alert>
                            @endif
                            @if(session('error'))
                                <x-app-alert variant="danger" class="mb-4">{{ session('error') }}</x-app-alert>
                            @endif
                            <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <x-app-input name="name" label="Seu nome" :value="old('name')" placeholder="Nome completo" required :error="$errors->first('name')" />
                                <x-app-input name="email" label="Seu e-mail" type="email" :value="old('email')" placeholder="seu@email.com" required :error="$errors->first('email')" />
                                <x-app-textarea name="message" label="Mensagem" rows="4" placeholder="Escreva sua mensagem..." required :error="$errors->first('message')">{{ old('message') }}</x-app-textarea>
                                <x-button type="submit" variant="primary">Enviar mensagem</x-button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Linha final do rodapé --}}
            <div class="border-t border-gray-200 dark:border-gray-800 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>© {{ date('Y') }} {{ isset($profile) ? $profile->name : 'Portfolio' }}.</span>
                    <div class="flex gap-4">
                        <a href="https://github.com/pvitorv/kit-components" target="_blank" rel="noopener noreferrer" class="hover:underline">Kit de componentes</a>
                        @if(isset($profile) && $profile->linkedin_url)
                            <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline">LinkedIn</a>
                        @endif
                        @if(isset($profile) && $profile->github_url)
                            <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline">GitHub</a>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    </div>
</x-layouts.app>
