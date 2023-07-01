<?php

namespace Sawirricardo\LaravelTranslationLoader\TranslationLoaders;

interface TranslationLoader
{
    public function loadTranslations($locale, $group);
}
