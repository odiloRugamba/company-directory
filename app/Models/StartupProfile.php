<?php

namespace App\Models;

use App\Enums\StartupStage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class StartupProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'tagline',
        'description',
        'website',
        'country',
        'city',
        'sector',
        'stage',
        'founded_year',
        'team_size',
        'logo',
    ];

    protected $casts = [
        'stage' => StartupStage::class,
        'team_size' => 'integer',
        'founded_year' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (StartupProfile $profile) {
            if (empty($profile->slug)) {
                $profile->slug = Str::slug($profile->company_name);
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
        $name = urlencode($this->company_name);
        return "https://ui-avatars.com/api/?name={$name}&background=1A3260&color=00C896&size=128";
    }

    public function scopeBySector(Builder $query, string $sector): Builder
    {
        return $query->where('sector', $sector);
    }

    public function scopeByStage(Builder $query, string $stage): Builder
    {
        return $query->where('stage', $stage);
    }

    public function scopeByCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('company_name', 'like', "%{$term}%")
              ->orWhere('tagline', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
