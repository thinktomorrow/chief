<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Thinktomorrow\Chief\Site\Menu\MenuItem;

class ImportMenu implements ToCollection
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

        foreach($rows as $row) {

            // Ignore empty rows or invalid ID references
            if(! isset($row[$idIndex]) || $row[$idIndex] == 'ID') {
                continue;
            }

            $encryptedId = $row[$idIndex];

            try {
                $menuItemId = decrypt($encryptedId);
            } catch (DecryptException $e) {
                $this->writeToOutput('Invalid menu item id reference: ' . $encryptedId, 'error');

                continue;
            }

            $menuItem = MenuItem::find($menuItemId);
            foreach($this->locales as $locale) {

                $labelValueIndex = array_search($locale.'_label', $this->headers);
                $urlValueIndex = array_search($locale.'_url', $this->headers);

                if(! $labelValueIndex) {
                    throw new \Exception('Label column not found for locale: '.$locale . '. Expected column name: '.$locale.'_label');
                }

                if(! $urlValueIndex) {
                    throw new \Exception('Url column not found for locale: '.$locale . '. Expected column name: '.$locale.'_url');
                }

                $menuItem->setDynamic('label.' . $locale, $row[$labelValueIndex]);
                $menuItem->setDynamic('url.' . $locale, $row[$urlValueIndex]);
            }

            $menuItem->save();
            $this->writeToOutput('Updated menu item labels and urls: '.$menuItemId);
        }
    }

    private function writeToOutput($message, string $type = 'info')
    {
        if($this->output) {
            $this->output->{$type}($message);
        }
    }

    public function setOutput(OutputStyle $output): static
    {
        $this->output = $output;

        return $this;
    }
}
