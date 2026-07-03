<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'name',
        'description',
        'url',
        'github_url',
        'thumbnail_url',
        'order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->title;
    }

    public function getThumbnailOrDefaultAttribute(): ?string
    {
        return $this->thumbnail_url ?? null;
    }

    /**
     * URL da miniatura pronta para exibir no front.
     * Uploads em storage/app/public usam caminho relativo /storage/...
     */
    public function getThumbnailDisplayUrlAttribute(): ?string
    {
        if (empty($this->thumbnail_url)) {
            return null;
        }
        if (str_starts_with($this->thumbnail_url, 'http://') || str_starts_with($this->thumbnail_url, 'https://')) {
            return $this->thumbnail_url;
        }
        return PublicStorageUrl::url($this->thumbnail_url);
    }
}
