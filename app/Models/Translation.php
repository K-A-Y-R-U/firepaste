<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    protected $fillable = [
        'key',
        'translations',
        'group',
    ];

    protected $casts = [
        'translations' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearTranslationCache();
        });

        static::deleted(function () {
            self::clearTranslationCache();
        });
    }

    public static function clearTranslationCache(): void
    {
        $languages = Language::getActiveCodes();
        foreach ($languages as $code) {
            Cache::forget("translations.{$code}");
        }
    }

    public function getTranslation(string $locale): ?string
    {
        return $this->translations[$locale] ?? null;
    }

    public function setTranslation(string $locale, string $value): void
    {
        $translations = $this->translations ?? [];
        $translations[$locale] = $value;
        $this->translations = $translations;
    }

    /**
     * Obtener todas las traducciones para un idioma desde caché.
     */
    public static function getForLocale(string $locale): array
    {
        return Cache::rememberForever("translations.{$locale}", function () use ($locale) {
            $translations = [];

            self::all()->each(function ($translation) use ($locale, &$translations) {
                if (isset($translation->translations[$locale])) {
                    $translations[$translation->key] = $translation->translations[$locale];
                }
            });

            return $translations;
        });
    }

    /**
     * Exportar traducciones de la BD a archivos JSON.
     * Laravel usa estos archivos para __() y trans().
     */
    public static function syncToFiles(): void
    {
        $languages = Language::getActiveCodes();

        foreach ($languages as $code) {
            $translations = self::getForLocale($code);
            $filePath = lang_path("{$code}.json");

            file_put_contents(
                $filePath,
                json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }
    }

    /**
     * Importar traducciones desde archivos JSON a la BD.
     */
    public static function importFromFiles(): int
    {
        $languages = Language::getActiveCodes();
        $imported  = 0;

        foreach ($languages as $code) {
            $filePath = lang_path("{$code}.json");

            if (file_exists($filePath)) {
                $fileTranslations = json_decode(file_get_contents($filePath), true) ?? [];

                foreach ($fileTranslations as $key => $value) {
                    $translation = self::firstOrNew(['key' => $key]);
                    $current = $translation->translations ?? [];
                    $current[$code] = $value;
                    $translation->translations = $current;
                    $translation->save();
                    $imported++;
                }
            }
        }

        self::clearTranslationCache();
        return $imported;
    }
}