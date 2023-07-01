<?php

return [
    'translation_loaders' => [
        \Sawirricardo\LaravelTranslationLoader\TranslationLoaders\Db::class,
    ],

    'model' => \Sawirricardo\LaravelTranslationLoader\Models\Translation::class,

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
];
