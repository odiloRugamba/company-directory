<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon'];

    protected static function booted(): void
    {
        static::creating(function (Sector $sector) {
            if (empty($sector->slug)) {
                $sector->slug = Str::slug($sector->name);
            }
        });
    }
}
