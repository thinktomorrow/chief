<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;

class ImportAssetText implements ToCollection
{
    private array $headers;

    private array $locales;

    private ?OutputStyle $output = null;

    public function __construct(array $headers, array $locales)
    {
        $this->headers = $headers;
        $this->locales = $locales;
    }

    public function collection(Collection $rows)
    {
        $idIndex = array_search('id', $this->headers);

        foreach ($rows as $row) {

            // Ignore empty rows or invalid ID references
            if (! isset($row[$idIndex]) || $row[$idIndex] == 'ID') {
                continue;
            }

            $encryptedId = $row[$idIndex];

            try {
                $assetId = decrypt($encryptedId);
                $asset = Asset::find($assetId);
            } catch (DecryptException $e) {
                $this->writeToOutput('Invalid asset id reference: '.$encryptedId, 'error');

                continue;
            }

            // If basename different? Change it...
            $baseNameIndex = array_search('Bestandsnaam', $this->headers);
            $basename = $row[$baseNameIndex] ?? null;

            if ($asset->getBasename() !== $basename) {
                app(FileApplication::class)->updateFileName($asset->id, $basename);
                $this->writeToOutput('Updated asset basename for: '.$assetId);
            }

            // Alt text for each locale
            $altTexts = [];

            foreach ($this->locales as $locale) {

                $altValueIndex = array_search($locale.' alt', $this->headers);

                if (! $altValueIndex) {
                    throw new \Exception('Alt column not found for locale: '.$locale.'. Expected column name: '.$locale.' alt');
                }

                $altTexts[$locale] = $row[$altValueIndex];
            }

            // If alt texts are empty, skip updating
            if (array_filter($altTexts, fn ($text) => ! empty($text)) === []) {
                continue;
            }

            app(FileApplication::class)->updateAssetData($assetId, [
                'alt' => $altTexts,
            ]);

            $this->writeToOutput('Updated asset alt text for: '.$assetId);
        }

    }

    private function writeToOutput($message, string $type = 'info')
    {
        if ($this->output) {
            $this->output->{$type}($message);
        }
    }

    public function setOutput(OutputStyle $output): static
    {
        $this->output = $output;

        return $this;
    }
}
