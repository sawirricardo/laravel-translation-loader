<?php

use function PHPUnit\Framework\assertEquals;
use Sawirricardo\LaravelTranslationLoader\Tests\DummyLoader;
use Sawirricardo\LaravelTranslationLoader\TranslationLoaders\Db;

it('will not use database translations if the provider is not configured', function () {
    config()->set('translation-loader.translation_loaders', []);
    assertEquals('group.key', trans('group.key'));
});

it('will merge translation from all providers', function () {
    config()->set('translation-loader.translation_loaders', [
        Db::class,
        DummyLoader::class,
    ]);

    createTrans('db', 'key', ['en' => 'db']);
    assertEquals('db', trans('db.key'));
    assertEquals('this is dummy', trans('dummy.dummy'));
});
