<?php

namespace Sawirricardo\LaravelTranslationLoader\Commands;

use Illuminate\Console\Command;
use Sawirricardo\LaravelTranslationLoader\Facades\TranslationLoader;

class ScanTranslationsCommand extends Command
{
    public $signature = 'translations:sync';

    public $description = 'Sync Translation Files';

    public function handle(): int
    {
        $this->comment('Syncing Translation...');

        TranslationLoader::sync();

        $this->comment('All done');

        return self::SUCCESS;
    }
}
