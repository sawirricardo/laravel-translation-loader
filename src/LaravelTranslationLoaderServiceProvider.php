<?php

namespace Sawirricardo\LaravelTranslationLoader;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Translation\Translator;
use Sawirricardo\LaravelTranslationLoader\Commands\ScanTranslationsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTranslationLoaderServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-translation-loader')
            ->hasConfigFile()
            ->hasMigration('create_translations_table')
            ->hasCommand(ScanTranslationsCommand::class);
    }

    public function provides()
    {
        return ['translator', 'translation.loader'];
    }

    public function packageRegistered()
    {
        $this->registerLoader();

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app->getLocale();

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app->getFallbackLocale());

            return $trans;
        });
    }

    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            $class = config('translation-loader.translation_manager');

            return new $class($app['files'], [__DIR__.'/lang', $app['path.lang']]);
        });
    }
}
