<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HubClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'source_type',
        'source_key',
        'clicked_at',
    ];

    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    public static function record(string $sourceType, string $sourceKey): void
    {
        static::create([
            'source_type' => $sourceType,
            'source_key' => $sourceKey,
            'clicked_at' => now(),
        ]);
    }

    public static function countFor(string $sourceType, string $sourceKey): int
    {
        return static::query()
            ->where('source_type', $sourceType)
            ->where('source_key', $sourceKey)
            ->count();
    }

    public static function countLastDays(int $days = 7): int
    {
        return static::query()
            ->where('clicked_at', '>=', now()->subDays($days))
            ->count();
    }
}
