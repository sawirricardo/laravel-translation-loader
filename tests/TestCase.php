<?php

namespace Sawirricardo\LaravelTranslationLoader\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sawirricardo\LaravelTranslationLoader\LaravelTranslationLoaderServiceProvider;

class TestCase extends Orchestra
{
    protected $translation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translation = createTrans('group', 'key', ['en' => 'english', 'nl' => 'nederlands']);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTranslationLoaderServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = __DIR__.'/fixtures/lang';
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $migration = include __DIR__.'/../database/migrations/create_translations_table.php.stub';
        $migration->up();
    }
}
