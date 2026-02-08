@props([
    'title' => null,
    'metaDescription' => null,
    'metaImage' => null,
    'canonicalUrl' => null,
    'noindex' => false,
])

@php
    $pageTitle = $title ?? config('app.name', 'Portfólio');
    $canonical = $canonicalUrl ?? url()->current();
@endphp
<!DOCTYPE html>
<html
    lang="pt-BR"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Portfólio profissional com projetos e informações de contato.' }}">
    @if($noindex)
        <meta name="robots" content="noindex, nofollow">
    @endif
    <link rel="canonical" href="{{ $canonical }}">
    {{-- Open Graph (Facebook, LinkedIn, etc.) --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Portfólio profissional com projetos e informações de contato.' }}">
    <meta property="og:url" content="{{ $canonical }}">
    @if($metaImage)
        <meta property="og:image" content="{{ $metaImage }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta property="og:site_name" content="{{ config('app.name', 'Portfólio') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="{{ $metaImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Portfólio profissional com projetos e informações de contato.' }}">
    @if($metaImage)
        <meta name="twitter:image" content="{{ $metaImage }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @stack('structuredData')
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback: Tailwind + Alpine via CDN até você rodar "npm run build" --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { darkMode: 'class' };
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    {{ $slot }}
</body>
</html>
