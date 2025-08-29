<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'index_number',   // student id
        'avatar_path',    // storage path (public disk)
        'type',
        'pathway',
    ];

    /**
     * Hidden when serialized.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Append computed attributes to arrays/JSON.
     */
    protected $appends = ['avatar_url'];

    /**
     * Public URL to the stored avatar (or null if none).
     * Requires: php artisan storage:link
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar_path) {
            return null; // or return asset('images/avatar-placeholder.png');
        }

        // If an absolute URL was stored, return as-is.
        if (preg_match('#^https?://#i', $this->avatar_path)) {
            return $this->avatar_path;
        }

        // Normal case: path like "avatars/abc.jpg" on the "public" disk.
        return Storage::disk('public')->url($this->avatar_path);
        // Equivalent: return asset('storage/'.$this->avatar_path);
    }

    /**
     * Relationships
     */
    public function results(): HasMany
    {
        return $this->hasMany(\App\Models\Result::class);
    }

    /** =========================
     *  Achievements (NEW)
     *  ========================= */
    public function achievements(): HasMany
    {
        return $this->hasMany(\App\Models\Achievement::class);
    }

    /**
     * Optional helper: include achievements_count in queries.
     * Usage: User::withAchievementsCount()->paginate(...)
     */
    public function scopeWithAchievementsCount($query)
    {
        return $query->withCount('achievements');
    }
}
