<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\App\Console\ReadsCsv;
use Thinktomorrow\Squanto\Database\DatabaseLine;
use Thinktomorrow\Squanto\Domain\Exceptions\InvalidLineKeyException;
use Thinktomorrow\Squanto\Domain\LineKey;

class ImportMenuCommand extends BaseCommand
{
    use ReadsCsv;

    protected $signature = 'chief:import-menu {file}';
    protected $description = 'Import menu translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $file = $this->argument('file');
        $headers = (new HeadingRowImport)->toArray($file)[0][0];
        $locales = config('chief.locales', []);

        Excel::import((new ImportMenu(
            $headers,
            $locales
        ))->setOutput($this->output), $file);

        $this->info('Finished import of menu for locale ' . implode(',',$locales) . ' 🤘');
    }
}
