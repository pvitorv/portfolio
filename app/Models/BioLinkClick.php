<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BioLinkClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'bio_link_id',
        'clicked_at',
    ];

    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    public function bioLink(): BelongsTo
    {
        return $this->belongsTo(BioLink::class);
    }
}
