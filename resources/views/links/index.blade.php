<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="{{ $profile?->title ?? 'Links e projetos' }}">
    <title>{{ $profile?->name ?? 'Links' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|inter:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .link-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: border-color 0.2s, background 0.2s, transform 0.15s;
        }
        .link-card:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.16);
        }
        .share-menu {
            background: rgba(15, 15, 20, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="min-h-screen bg-[#0a0a0f] text-white antialiased font-sans">
    {{-- fundo sutil --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[400px] rounded-full bg-violet-600/10 blur-[100px]"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[300px] rounded-full bg-blue-600/8 blur-[80px]"></div>
    </div>

    <main
        class="relative max-w-[420px] mx-auto px-4 py-10 sm:py-14"
        x-data="linkHubPage()"
    >
        {{-- cabeçalho --}}
        <header class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500/20 to-blue-500/20 border border-white/10 text-xl font-semibold text-white/90 mb-4">
                {{ strtoupper(substr($profile?->name ?? '?', 0, 1)) }}
            </div>

            <h1 class="text-xl font-semibold tracking-tight text-white">{{ $profile?->name ?? 'Links' }}</h1>

            @if($profile?->title)
                <p class="mt-1 text-sm text-white/50">{{ $profile->title }}</p>
            @endif

            @if($profile?->bio)
                <p class="mt-3 text-xs leading-relaxed text-white/40 max-w-xs mx-auto">{{ str($profile->bio)->limit(120) }}</p>
            @endif

            {{-- compartilhar página --}}
            <button
                type="button"
                x-on:click="sharePage()"
                class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-medium text-white/70 border border-white/10 hover:border-white/20 hover:text-white hover:bg-white/5 transition"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                Compartilhar página
            </button>
        </header>

        @php
            $hasItems = collect($sections)->contains(fn ($section) => $section['items']->isNotEmpty());
        @endphp

        @if(!$hasItems)
            <p class="text-center text-white/40 text-sm py-8">Nenhum link disponível.</p>
        @else
            <div class="space-y-7">
                @foreach($sections as $section)
                    @if($section['items']->isEmpty())
                        @continue
                    @endif

                    <section>
                        @if(count($sections) > 1)
                            <h2 class="text-[10px] font-semibold uppercase tracking-[0.15em] text-white/30 mb-3 px-1">{{ $section['label'] }}</h2>
                        @endif

                        <div class="space-y-2.5">
                            @foreach($section['items'] as $item)
                                <article
                                    class="link-card rounded-xl overflow-hidden"
                                    x-data="{ open: false }"
                                >
                                    <div class="flex items-stretch">
                                        <a
                                            href="{{ $item->href }}"
                                            class="flex-1 min-w-0 px-4 py-3.5 block group"
                                        >
                                            <span class="block text-sm font-semibold text-white group-hover:text-violet-200 transition-colors">{{ $item->title }}</span>
                                            @if($item->description)
                                                <span class="mt-0.5 block text-xs leading-relaxed text-white/45">{{ $item->description }}</span>
                                            @endif
                                        </a>

                                        <button
                                            type="button"
                                            x-on:click="open = !open"
                                            class="shrink-0 px-3 flex items-center text-white/30 hover:text-white/70 border-l border-white/5 hover:bg-white/5 transition"
                                            aria-label="Compartilhar {{ $item->title }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                        </button>
                                    </div>

                                    <div x-show="open" x-cloak x-transition class="share-menu border-t border-white/5 px-3 py-2 flex flex-wrap gap-1.5">
                                        <button type="button" x-on:click="copyLink(@js($item->href), @js($item->title)); open = false" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-white/70 hover:text-white hover:bg-white/10 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            Copiar
                                        </button>
                                        <button type="button" x-on:click="shareWhatsApp(@js($item->title), @js($item->href)); open = false" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-white/70 hover:text-white hover:bg-white/10 transition">
                                            WhatsApp
                                        </button>
                                        <button type="button" x-on:click="shareNative(@js($item->title), @js($item->description ?? ''), @js($item->href)); open = false" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-white/70 hover:text-white hover:bg-white/10 transition">
                                            Mais apps…
                                        </button>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @endif

        <p class="mt-10 text-center text-[10px] text-white/20 tracking-wide">portfolio · links</p>

        {{-- toast copiado --}}
        <div
            x-show="toast"
            x-cloak
            x-transition
            class="fixed bottom-6 left-1/2 -translate-x-1/2 px-4 py-2 rounded-full bg-white text-gray-900 text-xs font-medium shadow-lg"
            x-text="toast"
        ></div>
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function linkHubPage() {
            return {
                toast: '',
                pageUrl: @js(url()->current()),
                pageTitle: @js($profile?->name ?? 'Links'),

                showToast(msg) {
                    this.toast = msg;
                    setTimeout(() => this.toast = '', 2000);
                },

                async copyLink(url, title) {
                    const text = url;
                    try {
                        await navigator.clipboard.writeText(text);
                        this.showToast('Link copiado!');
                    } catch {
                        this.showToast('Não foi possível copiar');
                    }
                },

                shareWhatsApp(title, url) {
                    const text = encodeURIComponent(title + '\n' + url);
                    window.open('https://wa.me/?text=' + text, '_blank');
                },

                async shareNative(title, text, url) {
                    if (navigator.share) {
                        try {
                            await navigator.share({ title, text: text || title, url });
                        } catch (_) {}
                    } else {
                        await this.copyLink(url, title);
                    }
                },

                sharePage() {
                    this.shareNative(this.pageTitle, 'Confira meus links', this.pageUrl);
                },
            };
        }
    </script>
</body>
</html>
