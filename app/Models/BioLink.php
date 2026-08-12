<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BioLink extends Model
{
    protected $fillable = [
        'title',
        'description',
        'url',
        'type',
        'sort_order',
        'is_active',
        'click_count',
        'last_clicked_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_clicked_at' => 'datetime',
            'click_count' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(BioLinkClick::class);
    }

    public function getDestinationUrlAttribute(): string
    {
        return match ($this->type) {
            'whatsapp' => 'https://wa.me/'.preg_replace('/\D/', '', $this->url),
            'email' => str_starts_with($this->url, 'mailto:') ? $this->url : 'mailto:'.$this->url,
            'phone' => str_starts_with($this->url, 'tel:') ? $this->url : 'tel:'.$this->url,
            default => $this->url,
        };
    }

    public function recordClick(): void
    {
        $this->increment('click_count');
        $this->update(['last_clicked_at' => now()]);

        BioLinkClick::create([
            'bio_link_id' => $this->id,
            'clicked_at' => now(),
        ]);
    }

    public function clicksLastDays(int $days = 7): int
    {
        return $this->clicks()
            ->where('clicked_at', '>=', now()->subDays($days))
            ->count();
    }

    public static function activeOrdered()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
