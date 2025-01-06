<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Thinktomorrow\Chief\App\Console\BaseCommand;

class ImportResourceCommand extends BaseCommand
{
    protected $signature = 'chief:import-resource {file : the file to import}';
    protected $description = 'Import model translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $file = $this->argument('file');
        $headers = (new HeadingRowImport)->toArray($file)[0][0];
        $locales = config('chief.locales', []);

        // Remove headers which are added automatically - these are integers
        $headers = array_filter($headers, function ($header) {
            return ! is_int($header);
        });

        $idColumn = $this->ask('Which column contains the ID references? Choose one of: '.implode(', ', $headers), $headers[0]);

        if (! $idColumn || ! in_array($idColumn, $headers)) {
            $this->error('No or invalid column for the ID references selected');

            return;
        }

        $column = $this->ask('Which column would you like to import? Choose one of: '.implode(', ', $headers));

        if (! $column || ! in_array($column, $headers) || $column === $idColumn) {
            $this->error('No or invalid column for import selected');

            return;
        }

        // Fixed non-localized import
        if ($column === 'tekst') {
            $locale = 'x';
        } else {
            $defaultLocale = in_array(strtolower($column), $locales) ? strtolower($column) : null;
            $locale = $this->ask('Which locale does this column represent? Choose one of: '.implode(', ', $locales), $defaultLocale);

            if (! $locale || ! in_array($locale, $locales)) {
                $this->error('No or invalid locale selected');

                return;
            }
        }

        Excel::import((new ImportFieldLines(
            array_search($idColumn, $headers),
            array_search($column, $headers),
            $locale
        ))->setOutput($this->output), $file);

        $this->info('Finished import of fields for locale '.$locale . ' 🤘');
    }
}
