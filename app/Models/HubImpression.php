<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class HubImpression extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'source_type',
        'source_key',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public static function record(string $sourceType, string $sourceKey): void
    {
        static::create([
            'source_type' => $sourceType,
            'source_key' => $sourceKey,
            'viewed_at' => now(),
        ]);
    }

    public static function countFor(string $sourceType, string $sourceKey): int
    {
        return static::query()
            ->where('source_type', $sourceType)
            ->where('source_key', $sourceKey)
            ->count();
    }

    public static function countForLastDays(string $sourceType, string $sourceKey, int $days = 7): int
    {
        return static::query()
            ->where('source_type', $sourceType)
            ->where('source_key', $sourceKey)
            ->where('viewed_at', '>=', now()->subDays($days))
            ->count();
    }

    public static function lastFor(string $sourceType, string $sourceKey): ?Carbon
    {
        $at = static::query()
            ->where('source_type', $sourceType)
            ->where('source_key', $sourceKey)
            ->max('viewed_at');

        return $at ? Carbon::parse($at) : null;
    }
}
