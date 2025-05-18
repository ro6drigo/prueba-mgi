<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'state',
        'timestamp',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
        ];
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }
}
