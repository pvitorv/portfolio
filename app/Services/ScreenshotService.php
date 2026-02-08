<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ScreenshotService
{
    /**
     * Obtém a URL de uma miniatura/screenshot da página pelo link do projeto.
     * Usa a API Microlink (plano gratuito com limites).
     */
    public function getScreenshotUrl(string $url): ?string
    {
        $response = Http::timeout(15)->get('https://api.microlink.io', [
            'url' => $url,
            'screenshot' => true,
            'meta' => false,
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        return $data['data']['screenshot']['url'] ?? null;
    }
}
