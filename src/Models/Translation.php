<?php

namespace Sawirricardo\LaravelTranslationLoader\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Translation extends Model
{
    use SoftDeletes;
    use HasTranslations;

    public $translatable = ['text'];

    protected $casts = ['text' => 'array'];

    protected $fillable = [
        'group',
        'key',
        'text',
        'namespace',
    ];

    public static function getTranslationsForGroup($locale, $group): array
    {
        return cache()->rememberForever(static::getCacheKey($group, $locale), function () use ($group, $locale) {
            return static::query()
                ->where('group', $group)
                ->get()
                ->reduce(function ($lines, self $languageLine) use ($locale) {
                    $translation = $languageLine->getTranslation($this->getTranslatableAttributes()[0], $locale);

                    if (! is_null($translation)) {
                        data_set($lines, $languageLine->key, $translation);
                    }

                    return $lines;
                }) ?? [];
        });
    }

    public static function getTranslatableLocales(): array
    {
        return config('filament-translations.locals');
    }

    public static function getCacheKey($group, $locale)
    {
        return implode('.', [
            'sawirricardo',
            'laravel-translation-loader',
            $group,
            $locale,
        ]);
    }

    protected static function booted()
    {
        static::saved(function ($model) {
            $model->flushGroupCache();
        });
        static::deleted(function ($model) {
            $model->flushGroupCache();
        });
    }

    public function flushGroupCache()
    {
        foreach ($this->getTranslatableAttributes() as $translatable) {
            foreach ($this->getTranslatedLocales($translatable) as $locale) {
                cache()->forget(static::getCacheKey($this->group, $locale));
            }
        }
    }
}
