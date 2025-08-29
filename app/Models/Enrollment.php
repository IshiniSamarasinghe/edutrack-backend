<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = ['user_id', 'module_offering_id'];

    public function offering(): BelongsTo
    {
        return $this->belongsTo(ModuleOffering::class, 'module_offering_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
