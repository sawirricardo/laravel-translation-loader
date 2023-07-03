# Store your translations in the database or other sources, using the modern packages

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sawirricardo/laravel-translation-loader.svg?style=flat-square)](https://packagist.org/packages/sawirricardo/laravel-translation-loader)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sawirricardo/laravel-translation-loader/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sawirricardo/laravel-translation-loader/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sawirricardo/laravel-translation-loader/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sawirricardo/laravel-translation-loader/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sawirricardo/laravel-translation-loader.svg?style=flat-square)](https://packagist.org/packages/sawirricardo/laravel-translation-loader)

In a vanilla Laravel or Lumen installation you can use language files to localize your app. This package will enable the translations to be stored in the database. You can still use all the features of the trans function you know and love.

```php
trans('messages.welcome', ['name' => 'dayle']);
```

You can even mix using language files and the database. If a translation is present in both a file and the database, the database version will be returned.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-translation-loader.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-translation-loader)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require sawirricardo/laravel-translation-loader
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-translation-loader-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-translation-loader-config"
```

This is the contents of the published config file:

```php
'translation_loaders' => [
    \Sawirricardo\LaravelTranslationLoader\TranslationLoaders\Db::class,
],

'model' => \Sawirricardo\LaravelTranslationLoader\Models\Translation::class,

'translation_manager' => \Sawirricardo\LaravelTranslationLoader\TranslationLoaderManager::class,

'locals' => [
    'en' => 'English',
    'ar' => 'Arabic',
    'pt_BR' => 'Português (Brasil)',
    'my' => 'Burmese',
    'id' => 'Bahasa Indonesia',
],

'paths' => [
    app_path(),
    resource_path('views'),
    base_path('vendor'),
],

'excluded_paths' => [
    //
],
```

## Usage

```php
use Sawirricardo\LaravelTranslationLoader\Models\Translation;

Translation::create([
   'group' => 'validation',
   'key' => 'required',
   'text' => ['en' => 'This is a required field', 'nl' => 'Dit is een verplicht veld'],
]);
```

You can fetch the translation with [Laravel's default trans function](https://laravel.com/docs/5.3/localization#retrieving-language-lines):

```php
trans('validation.required'); // returns 'This is a required field'

app()->setLocale('nl');

trans('validation.required'); // returns 'Dit is een verplicht veld'
```

## Creating your own translation providers

This package ships with a translation provider than can fetch translations from the database. If you're storing your translations in a yaml-file, a csv-file, or ... you can easily extend this package by creating your own translation provider.

A translation provider can be any class that implements the `Sawirricardo\LaravelTranslationLoader\TranslationLoaders\TranslationLoader`-interface. It contains only one method:

namespace Sawirricardo\LaravelTranslationLoader\TranslationLoaders;

interface TranslationLoader
{
public function loadTranslations($locale, $group);
}
Translation providers can be registered in the translation_loaders key of the config file.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Ricardo Sawir](https://github.com/sawirricardo)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
