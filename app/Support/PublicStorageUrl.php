<?php

namespace App\Support;

/**
 * URL pública para arquivos em storage/app/public.
 * Usa caminho relativo (/storage/...) para funcionar com ou sem www no mesmo domínio.
 */
final class PublicStorageUrl
{
    public static function url(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return '/storage/' . ltrim($path, '/');
    }
}