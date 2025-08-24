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
    use HasApiTokens;   // ✅ for Sanctum sessions
    use HasFactory, Notifiable;

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'index_number',   // ✅ your student id column
        'avatar_path',    // ✅ file path under storage/app/public/...
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
            'password'          => 'hashed', // Laravel will hash on set
        ];
    }

    /**
     * Append computed attributes on model -> toArray()/JSON.
     */
    protected $appends = [
        'avatar_url',
    ];

    /**
     * Accessor: public URL to the stored avatar (or null if none).
     * Requires `php artisan storage:link`.
     */
    public function getAvatarUrlAttribute(): ?string
{
    if (!$this->avatar_path) return null;

    // Make it absolute: http://localhost:8001/storage/avatars/...
    return asset(Storage::url($this->avatar_path));
}

    /**
     * Relationships.
     */
    public function results(): HasMany
    {
        return $this->hasMany(\App\Models\Result::class);
    }
}
