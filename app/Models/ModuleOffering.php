<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleOffering extends Model
{
    protected $fillable = [
        'module_id',
        'type',
        'pathway',
        'year',
        'semester',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'module_offering_id');
    }
}
