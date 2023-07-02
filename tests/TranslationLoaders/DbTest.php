<?php

use function PHPUnit\Framework\assertEquals;

it('can get a translation for the current app locale', function () {
    assertEquals('english', trans('group.key'));
});

it('can get a correct translation after the locale has been changed', function () {
    app()->setLocale('nl');

    assertEquals('nederlands', trans('group.key'));
});
