<?php

namespace App\Models;

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
     * Se for upload (path sem http), usa asset('storage/...'); senão usa a URL como está.
     */
    public function getThumbnailDisplayUrlAttribute(): ?string
    {
        if (empty($this->thumbnail_url)) {
            return null;
        }
        if (str_starts_with($this->thumbnail_url, 'http://') || str_starts_with($this->thumbnail_url, 'https://')) {
            return $this->thumbnail_url;
        }
        return asset('storage/' . ltrim($this->thumbnail_url, '/'));
    }
}
