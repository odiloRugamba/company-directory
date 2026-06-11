<?php

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'country',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'is_active' => 'boolean',
        ];
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=0F1F3D&color=00C896&size=128";
    }

    public function incubatorProfile(): HasOne
    {
        return $this->hasOne(IncubatorProfile::class);
    }

    public function startupProfile(): HasOne
    {
        return $this->hasOne(StartupProfile::class);
    }

    public function applicationsAsStartup(): HasMany
    {
        return $this->hasMany(Application::class, 'startup_id');
    }

    public function applicationsAsIncubator(): HasMany
    {
        return $this->hasMany(Application::class, 'incubator_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isIncubator(): bool
    {
        return $this->role === Role::Incubator;
    }

    public function isStartup(): bool
    {
        return $this->role === Role::Startup;
    }

    public function hasProfile(): bool
    {
        return match($this->role) {
            Role::Incubator => $this->incubatorProfile()->exists(),
            Role::Startup => $this->startupProfile()->exists(),
            Role::Admin => true,
        };
    }
}
