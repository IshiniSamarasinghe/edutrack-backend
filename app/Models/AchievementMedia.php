<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AchievementMedia extends Model
{
    protected $fillable = ['achievement_id','path','mime','size'];

    protected $appends = ['url'];

    public function achievement(): BelongsTo { return $this->belongsTo(Achievement::class); }

    public function getUrlAttribute(): string { return asset('storage/'.$this->path); }
}

