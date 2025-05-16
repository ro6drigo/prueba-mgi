<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;

class Container extends Model
{
    protected $fillable = [
        'id',
        'state',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
