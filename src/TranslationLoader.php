<?php

namespace Sawirricardo\LaravelTranslationLoader;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Sawirricardo\LaravelTranslationLoader\Models\Translation;
use Symfony\Component\Finder\SplFileInfo;

class TranslationLoader
{
    public function __construct(
        protected Filesystem $disk,
    ) {
    }

    public function sync()
    {
        [$trans, $__] = $this->filesWithTranslations();

        DB::transaction(function () use ($trans, $__) {
            Translation::query()->delete();

            $trans->each(function ($trans) {
                [$group, $key] = explode('.', $trans, 2);
                $namespaceAndGroup = explode('::', $group, 2);
                if (count($namespaceAndGroup) === 1) {
                    $namespace = '*';
                    $group = $namespaceAndGroup[0];
                } else {
                    [$namespace, $group] = $namespaceAndGroup;
                }

                $this->createOrUpdate($namespace, $group, $key);
            });

            $__->each(function ($default) {
                $this->createOrUpdate('*', '*', $default);
            });
        });
    }

    protected function createOrUpdate($namespace, $group, $key): void
    {
        /** @var Translation $translation */
        $translation = Translation::query()
            ->withTrashed()
            ->where('namespace', $namespace)
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        $defaultLocale = config('app.locale');

        if ($translation) {
            if (! $this->isCurrentTransForTranslationArray($translation, $defaultLocale)) {
                $translation->restore();
            }

            return;
        }

        $translation = Translation::make([
            'namespace' => $namespace,
            'group' => $group,
            'key' => $key,
            'text' => [],
        ]);

        if (! $this->isCurrentTransForTranslationArray($translation, $defaultLocale)) {
            $translation->save();
        }
    }

    protected function filesWithTranslations()
    {
        /*
         * This pattern is derived from Barryvdh\TranslationManager by Barry vd. Heuvel <barryvdh@gmail.com>
         *
         * https://github.com/barryvdh/laravel-translation-manager/blob/master/src/Manager.php
         */
        $functions = [
            'trans',
            'trans_choice',
            'Lang::get',
            'Lang::choice',
            'Lang::trans',
            'Lang::transChoice',
            '@lang',
            '@choice',
        ];

        $patternA =
            // See https://regex101.com/r/jS5fX0/4
            '[^\w]'. // Must not start with any alphanum or _
            '(?<!->)'. // Must not start with ->
            '('.implode('|', $functions).')'. // Must start with one of the functions
            "\(". // Match opening parentheses
            "[\'\"]". // Match " or '
            '('. // Start a new group to match:
            '([a-zA-Z0-9_\/-]+::)?'.
            '[a-zA-Z0-9_-]+'. // Must start with group
            "([.][^\1)$]+)+". // Be followed by one or more items/keys
            ')'. // Close group
            "[\'\"]". // Closing quote
            "[\),]"  // Close parentheses or new parameter
;

        $patternB =
            // See https://regex101.com/r/2EfItR/2
            '[^\w]'. // Must not start with any alphanum or _
            '(?<!->)'. // Must not start with ->
            '(__|Lang::getFromJson)'. // Must start with one of the functions
            '\('. // Match opening parentheses

            '[\"]'. // Match "
            '('. // Start a new group to match:
            '[^"]+'. //Can have everything except "
            //            '(?:[^"]|\\")+' . //Can have everything except " or can have escaped " like \", however it is not working as expected
            ')'. // Close group
            '[\"]'. // Closing quote

            '[\)]'  // Close parentheses or new parameter
;

        $patternC =
            // See https://regex101.com/r/VaPQ7A/2
            '[^\w]'. // Must not start with any alphanum or _
            '(?<!->)'. // Must not start with ->
            '(__|Lang::getFromJson)'. // Must start with one of the functions
            '\('. // Match opening parentheses

            '[\']'. // Match '
            '('. // Start a new group to match:
            "[^']+". //Can have everything except '
            //            "(?:[^']|\\')+" . //Can have everything except 'or can have escaped ' like \', however it is not working as expected
            ')'. // Close group
            '[\']'. // Closing quote

            '[\)]'  // Close parentheses or new parameter
;

        $trans = collect();
        $__ = collect();
        $excludedPaths = config('translation-loader.excluded_paths');

        /** @var SplFileInfo $file */
        foreach ($this->disk->allFiles($this->paths()) as $file) {
            $dir = dirname($file);
            if (str($dir)->startsWith($excludedPaths)) {
                continue;
            }

            if (preg_match_all("/{$patternA}/siU", $file->getContents(), $matches)) {
                $trans->push($matches[2]);
            }

            if (preg_match_all("/{$patternB}/siU", $file->getContents(), $matches)) {
                $__->push($matches[2]);
            }

            if (preg_match_all("/{$patternC}/siU", $file->getContents(), $matches)) {
                $__->push($matches[2]);
            }
        }

        return [$trans->flatten()->unique(), $__->flatten()->unique()];
    }

    protected function paths()
    {
        return config('translation-loader.paths');
    }

    protected function isCurrentTransForTranslationArray(Translation $translation, $locale)
    {
        if ($translation->group === '*') {
            return is_array(trans($translation->key, [], $locale));
        }

        if ($translation->namespace === '*') {
            return is_array(trans(implode('.', [$translation->group, $translation->key]), [], $locale));
        }

        return is_array(trans($translation->namespace.'::'.$translation->group.'.'.$translation->key, [], $locale));
    }
}
