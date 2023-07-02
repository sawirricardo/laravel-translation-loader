<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sawirricardo\LaravelTranslationLoader\Models\Translation;
use Sawirricardo\LaravelTranslationLoader\Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in(__DIR__);

function createTrans($group, $key, $text)
{
    return Translation::query()
        ->create(compact('group', 'key', 'text'));
}
