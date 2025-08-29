<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleOffering extends Model
{
    // if your table name is the default "module_offerings", no need to set $table
    protected $fillable = ['module_id', 'type', 'pathway', 'year', 'semester'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // IMPORTANT: this is what withCount('enrollments') uses
    public function enrollments(): HasMany
    {
        // column on enrollments table that points to this offering is "module_offering_id"
        return $this->hasMany(Enrollment::class, 'module_offering_id');
    }
}
