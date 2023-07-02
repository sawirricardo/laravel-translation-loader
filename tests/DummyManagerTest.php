<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;
use Sawirricardo\LaravelTranslationLoader\Tests\TranslationManagers\DummyManager;

beforeEach(function () {
    config()->set('translation-loader.translation_manager', DummyManager::class);
});

it('allows to change translation manager', function () {
    assertInstanceOf(DummyManager::class, app('translation.loader'));
});

it('can translate using dummy manager using file', function () {
    assertEquals('en value', trans('file.key'));
});

it('can translate using dummy manager using db', function () {
    createTrans('file', 'key', ['en' => 'en value from db']);
    assertEquals('en value from db', trans('file.key'));
});

it('can translate using dummy manager using file with incomplete db', function () {
    createTrans('file', 'key', ['nl' => 'nl value from db']);

    assertEquals('en value', trans('file.key'));
});

it('can translate using dummy manager using empty translation in db', function () {
    createTrans('file', 'key', ['en' => '']);
    
    // Some versions of Laravel changed the behaviour of what an empty "" translation value returns: the key name or an empty value
    // @see https://github.com/laravel/framework/issues/34218
    assertTrue(in_array(trans('file.key'), ['', 'file.key']));
});
