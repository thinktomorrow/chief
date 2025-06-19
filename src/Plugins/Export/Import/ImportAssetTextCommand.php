<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\App\Console\ReadsCsv;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ImportAssetTextCommand extends BaseCommand
{
    use ReadsCsv;

    protected $signature = 'chief:import-asset-text {file}';

    protected $description = 'Import asset texts / translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $file = $this->argument('file');

        HeadingRowFormatter::default('none');

        $headers = (new HeadingRowImport)->toArray($file)[0][0];
        $locales = ChiefSites::locales();

        // Remove headers which are added automatically - these are integers
        $headers = array_filter($headers, function ($header) {
            return ! is_int($header);
        });
        Excel::import((new ImportAssetText(
            $headers,
            $locales
        ))->setOutput($this->output), $file);

        $this->info('Finished import of asset texts for locale '.implode(',', $locales).' 🤘');
    }
}
