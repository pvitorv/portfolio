<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ScreenshotService
{
    /**
     * Gera screenshot da URL do projeto e salva em storage/app/public/projects/.
     * Retorna o path relativo (ex.: projects/abc.jpg) — não URL externa.
     */
    public function storeThumbnailFromProjectUrl(string $pageUrl): ?string
    {
        $screenshotUrl = $this->getScreenshotUrl($pageUrl);
        if ($screenshotUrl) {
            $stored = $this->downloadImageToPublicDisk($screenshotUrl);
            if ($stored) {
                return $stored;
            }
        }

        $ogImageUrl = $this->getOgImageUrl($pageUrl);
        if ($ogImageUrl) {
            return $this->downloadImageToPublicDisk($ogImageUrl);
        }

        return null;
    }

    /**
     * Se thumbnail_url for link externo (Microlink, etc.), baixa e salva localmente.
     */
    public function persistExternalThumbnail(?string $thumbnailUrl): ?string
    {
        if (empty($thumbnailUrl)) {
            return null;
        }

        if (str_starts_with($thumbnailUrl, 'http://') || str_starts_with($thumbnailUrl, 'https://')) {
            return $this->downloadImageToPublicDisk($thumbnailUrl) ?? $thumbnailUrl;
        }

        return $thumbnailUrl;
    }

    /**
     * Obtém URL temporária do screenshot via Microlink.
     */
    public function getScreenshotUrl(string $url): ?string
    {
        $response = Http::timeout(20)->get('https://api.microlink.io', [
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

    /**
     * Tenta obter og:image da página do projeto.
     */
    public function getOgImageUrl(string $pageUrl): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; PortfolioBot/1.0)'])
                ->get($pageUrl);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();

            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                return $this->resolveUrl($pageUrl, $m[1]);
            }
            if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/i', $html, $m)) {
                return $this->resolveUrl($pageUrl, $m[1]);
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    /**
     * Baixa imagem remota e grava em disco público.
     */
    public function downloadImageToPublicDisk(string $imageUrl): ?string
    {
        try {
            $response = Http::timeout(30)->get($imageUrl);

            if (! $response->successful()) {
                return null;
            }

            $body = $response->body();
            if ($body === '' || strlen($body) < 100) {
                return null;
            }

            $extension = $this->guessExtension($imageUrl, $response->header('Content-Type'));
            $filename = 'projects/' . Str::random(40) . '.' . $extension;

            Storage::disk('public')->put($filename, $body);

            return $filename;
        } catch (\Throwable) {
            return null;
        }
    }

    private function guessExtension(string $url, ?string $contentType): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            return $ext === 'jpeg' ? 'jpg' : $ext;
        }

        return match (true) {
            str_contains((string) $contentType, 'png') => 'png',
            str_contains((string) $contentType, 'gif') => 'gif',
            str_contains((string) $contentType, 'webp') => 'webp',
            default => 'jpg',
        };
    }

    private function resolveUrl(string $baseUrl, string $relativeOrAbsolute): string
    {
        if (str_starts_with($relativeOrAbsolute, 'http://') || str_starts_with($relativeOrAbsolute, 'https://')) {
            return $relativeOrAbsolute;
        }

        $parts = parse_url($baseUrl);
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? '';

        if (str_starts_with($relativeOrAbsolute, '//')) {
            return $scheme . ':' . $relativeOrAbsolute;
        }

        if (str_starts_with($relativeOrAbsolute, '/')) {
            return $scheme . '://' . $host . $relativeOrAbsolute;
        }

        $path = rtrim(dirname($parts['path'] ?? '/'), '/');

        return $scheme . '://' . $host . $path . '/' . $relativeOrAbsolute;
    }
}
