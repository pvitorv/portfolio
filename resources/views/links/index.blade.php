<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $profile?->name ?? 'Links' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-900 to-black text-white antialiased">
    <main class="max-w-md mx-auto px-5 py-12 sm:py-16">
        <header class="text-center mb-10">
            @if($profile?->photo_display_url)
                <img
                    src="{{ $profile->photo_display_url }}"
                    alt=""
                    class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover mx-auto ring-4 ring-white/10 shadow-xl"
                />
            @else
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full mx-auto bg-white/10 ring-4 ring-white/10 flex items-center justify-center text-3xl font-semibold text-white/70">
                    {{ strtoupper(substr($profile?->name ?? '?', 0, 1)) }}
                </div>
            @endif

            <h1 class="mt-5 text-2xl font-bold tracking-tight">{{ $profile?->name ?? 'Links' }}</h1>

            @if($profile?->title)
                <p class="mt-1 text-sm text-white/70">{{ $profile->title }}</p>
            @endif

            @if($profile?->bio)
                <p class="mt-4 text-sm leading-relaxed text-white/60 max-w-sm mx-auto">{{ $profile->bio }}</p>
            @endif
        </header>

        @php
            $hasItems = collect($sections)->contains(fn ($section) => $section['items']->isNotEmpty());
        @endphp

        @if(!$hasItems)
            <p class="text-center text-white/50 text-sm">Nenhum link disponível no momento.</p>
        @else
            <div class="space-y-8">
                @foreach($sections as $section)
                    @if($section['items']->isEmpty())
                        @continue
                    @endif

                    <section>
                        @if(count($sections) > 1)
                            <h2 class="text-xs font-semibold uppercase tracking-wider text-white/40 mb-3 px-1">{{ $section['label'] }}</h2>
                        @endif

                        <nav class="space-y-3" aria-label="{{ $section['label'] }}">
                            @foreach($section['items'] as $item)
                                <a
                                    href="{{ $item->href }}"
                                    class="group block w-full rounded-2xl border border-white/15 bg-white/10 px-5 py-4 shadow-lg backdrop-blur-sm transition hover:scale-[1.02] hover:border-white/30 hover:bg-white/15 active:scale-[0.98]"
                                >
                                    <div class="flex items-start gap-3">
                                        @if($item->thumbnailUrl)
                                            <img
                                                src="{{ $item->thumbnailUrl }}"
                                                alt=""
                                                class="w-12 h-12 rounded-lg object-cover shrink-0 ring-1 ring-white/10"
                                            />
                                        @endif
                                        <div class="min-w-0 flex-1 text-left">
                                            <span class="block font-medium text-white">{{ $item->title }}</span>
                                            @if($item->description)
                                                <span class="mt-1 block text-sm leading-snug text-white/55 line-clamp-2">{{ $item->description }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </nav>
                    </section>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
