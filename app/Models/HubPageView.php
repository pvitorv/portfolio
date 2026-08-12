<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HubPageView extends Model
{
    public $timestamps = false;

    protected $fillable = ['viewed_at'];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public static function record(): void
    {
        static::create(['viewed_at' => now()]);
    }

    public static function total(): int
    {
        return static::query()->count();
    }

    public static function lastDays(int $days = 7): int
    {
        return static::query()
            ->where('viewed_at', '>=', now()->subDays($days))
            ->count();
    }
}
