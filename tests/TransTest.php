<?php

use function PHPUnit\Framework\assertEquals;

it('can get translations for language files', function () {
    assertEquals('en value', trans('file.key'));
    assertEquals('page not found', trans('file.404.title'));
    assertEquals('This page does not exists', trans('file.404.message'));
});

it('can get translations for language files for the current locale', function () {
    app()->setLocale('nl');

    assertEquals('nl value', trans('file.key'));
    assertEquals('pagina niet gevonden', trans('file.404.title'));
    assertEquals('Deze pagina bestaat niet', trans('file.404.message'));
});
