<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\App\Console\ReadsCsv;

class ImportTextCommand extends BaseCommand
{
    use ReadsCsv;

    protected $signature = 'chief:import-text {file}';
    protected $description = 'Import text translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $file = $this->argument('file');
        $headers = (new HeadingRowImport)->toArray($file)[0][0];
        $locales = config('chief.locales', []);

        $idColumn = $this->ask('Which column contains the ID references? Choose one of: '.implode(', ', $headers), $headers[0]);

        if (! $idColumn || ! in_array($idColumn, $headers)) {
            $this->error('No or invalid column for the ID references selected');

            return;
        }

        $column = $this->ask('Which column would you like to import? Choose one of: '.implode(', ', $headers));

        if (! $column || ! in_array($column, $headers) || $column === $idColumn) {
            $this->error('No or invalid column for translations selected');

            return;
        }

        $locale = $this->ask('Which locale does this column represent? Choose one of: '.implode(', ', $locales), in_array(strtolower($column), $locales) ? strtolower($column) : null);

        if (! $locale || ! in_array($locale, $locales)) {
            $this->error('No or invalid locale selected');

            return;
        }

        Excel::import((new ImportText(
            array_search($idColumn, $headers),
            array_search($column, $headers),
            $locale
        ))->setOutput($this->output), $file);

        $this->info('Finished import of text for locale '.$locale . ' 🤘');
    }
}
