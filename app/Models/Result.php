<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    // allow these columns for create/update
    protected $fillable = [
        'user_id',
        'module_offering_id',
        'academic_year',
        'grade',
        'grade_points',
    ];

    // (optional) relationships here...
}
