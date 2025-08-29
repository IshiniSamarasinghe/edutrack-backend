<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $fillable = ['code','title','credits','description'];

    public function offerings()
    {
        return $this->hasMany(ModuleOffering::class);
    }

    // If enrollments are attached to offerings: enrollments(offering_id -> module_offerings.id)
    public function enrollments()
    {
        return $this->hasManyThrough(
            Enrollment::class,          // final model
            ModuleOffering::class,      // through
            'module_id',                // ModuleOffering.module_id
            'offering_id',              // Enrollment.offering_id
            'id',                       // Module.id
            'id'                        // ModuleOffering.id
        );
    }
}
