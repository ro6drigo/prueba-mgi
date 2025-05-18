<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'state',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
