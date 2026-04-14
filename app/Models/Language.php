<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'flag_emoji',
        'is_active',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_default' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Limpiar caché cuando se guarda o elimina un idioma
        static::saved(function () {
            Cache::forget('languages.active');
            Cache::forget('languages.all');
        });

        static::deleted(function () {
            Cache::forget('languages.active');
            Cache::forget('languages.all');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public static function getActive()
    {
        return Cache::rememberForever('languages.active', function () {
            return self::active()->get();
        });
    }

    public static function getDefault()
    {
        return self::where('is_default', true)->first()
            ?? self::where('code', 'es')->first()
            ?? self::first();
    }

    public static function getActiveCodes()
    {
        return self::active()->pluck('code')->toArray();
    }
}