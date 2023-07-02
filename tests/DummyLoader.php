<?php

namespace Sawirricardo\LaravelTranslationLoader\Tests;

use Sawirricardo\LaravelTranslationLoader\TranslationLoaders\TranslationLoader;

class DummyLoader implements TranslationLoader
{
    public function loadTranslations($locale, $group, $namespace = null)
    {
        return ['dummy' => 'this is dummy'];
    }
}
