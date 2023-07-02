<?php

namespace Sawirricardo\LaravelTranslationLoader\TranslationLoaders;

class Db implements TranslationLoader
{
    public function loadTranslations($locale, $group)
    {
        return config('translation-loader.model')::getTranslationsForGroup($locale, $group);
    }
}
