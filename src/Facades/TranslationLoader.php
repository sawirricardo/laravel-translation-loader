<?php

namespace Sawirricardo\LaravelTranslationLoader\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sawirricardo\LaravelTranslationLoader\TranslationLoader
 */
class TranslationLoader extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sawirricardo\LaravelTranslationLoader\TranslationLoader::class;
    }
}
