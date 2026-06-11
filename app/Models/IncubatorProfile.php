<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class IncubatorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_name',
        'slug',
        'description',
        'website',
        'country',
        'city',
        'focus_sectors',
        'supported_stages',
        'capacity',
        'logo',
        'is_verified',
        'founded_year',
    ];

    protected $casts = [
        'focus_sectors' => 'array',
        'supported_stages' => 'array',
        'is_verified' => 'boolean',
        'capacity' => 'integer',
        'founded_year' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (IncubatorProfile $profile) {
            if (empty($profile->slug)) {
                $profile->slug = Str::slug($profile->organization_name);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        $name = urlencode($this->organization_name);
        return "https://ui-avatars.com/api/?name={$name}&background=0F1F3D&color=00C896&size=128";
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeByCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }

    public function scopeBySector(Builder $query, string $sector): Builder
    {
        return $query->whereJsonContains('focus_sectors', $sector);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('organization_name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%");
        });
    }
}
