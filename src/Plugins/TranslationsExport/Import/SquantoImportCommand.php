<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Import;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\App\Console\ReadsCsv;
use Thinktomorrow\Squanto\Database\DatabaseLine;
use Thinktomorrow\Squanto\Domain\Exceptions\InvalidLineKeyException;
use Thinktomorrow\Squanto\Domain\LineKey;

class SquantoImportCommand extends BaseCommand
{
    use ReadsCsv;

    protected $signature = 'chief:trans-import {file}';
    protected $description = 'Import model translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $file = $this->argument('file');
        $headers = $this->getColumnHeaders($file);
        $locales = config('squanto.locales', []);

        $keyColumn = $this->ask('which column contains the squanto keys? Choose one of: '.implode(', ', $headers), $headers[0]);

        if(!$keyColumn || !in_array($keyColumn, $headers)) {
            $this->error('No or invalid column for the key selected');
            return;
        }

        $column = $this->ask('which column contains the new translations and should be imported? Choose one of: '.implode(', ', $headers));

        if(!$column || !in_array($column, $headers) || $column === $keyColumn) {
            $this->error('No or invalid column for translations selected');
            return;
        }

        $locale = $this->ask('Which locale is the mapped to? Choose one of: '.implode(', ', $locales), in_array(strtolower($column), $locales) ? strtolower($column) : null);

        if(!$locale || !in_array($locale, $locales)) {
            $this->error('No or invalid locale selected');
            return;
        }

        $selectedKeyColumnIndex = array_search($keyColumn, $headers);
        $selectedColumnIndex = array_search($column, $headers);

        $i = 0;
        $this->loop($file, function ($row) use($selectedKeyColumnIndex, $selectedColumnIndex, $locale, &$i) {

            if($i == 0) {
                $i++;
                return;
            }

            $key = $row[$selectedKeyColumnIndex];

            if(!isset($row[$selectedColumnIndex])) {
                dd($row);
            }

            try{
                $lineKey = LineKey::fromString($key);
            } catch (InvalidLineKeyException $e) {
                dd($row);
                $this->warn('Invalid key: '.$key);
                return;
            }

            $value = $row[$selectedColumnIndex];

            if($line = DatabaseLine::findByKey($lineKey)) {
                $values = json_decode($line->values, true);
                $values['value'][$locale] = $value;

                $line->update(['values' => $values]);
                $this->info('Updated translation for key: '.$key);
            } else {
                // TODO: we could also opt for insert here...
                $this->warn('No line found for key: '.$key);
            }

            $i++;
        });

        $this->info('Finished squanto import of locale '.$locale . ' 🤘');
    }

    private function getColumnHeaders(string $path): array
    {
        $handle = fopen($path, "r");
        $headers = fgetcsv($handle, 4000, ",");

        fclose($handle);

        return $headers;
    }

    //    private function decryptModelReference(string $encryptedModelReference): ModelReference
    //    {
    //        return ModelReference::fromString(decrypt($encryptedModelReference));
    //    }
}
