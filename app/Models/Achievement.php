<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    protected $fillable = ['user_id','title','description','link','date'];

    protected $casts = ['date' => 'date'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function media(): HasMany { return $this->hasMany(AchievementMedia::class); }
}
