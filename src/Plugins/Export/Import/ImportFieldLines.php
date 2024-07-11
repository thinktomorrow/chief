<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Thinktomorrow\Chief\Plugins\Export\Export\Lines\FieldLine;

class ImportFieldLines implements ToCollection
{
    private string $idIndex;
    private string $locale;
    private string $columnIndex;
    private ?OutputStyle $output = null;

    public function __construct(string $idIndex, string $columnIndex, string $locale)
    {
        $this->idIndex = $idIndex;
        $this->columnIndex = $columnIndex;
        $this->locale = $locale;
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row) {

            // Ignore empty rows or invalid ID references
            if(! isset($row[$this->idIndex])) {
                continue;
            }

            $encryptedId = $row[$this->idIndex];

            try {
                $fieldReference = FieldReference::fromEncryptedKey($encryptedId);
            } catch (DecryptException|ModelNotFoundException $e) {
                if($this->output && $encryptedId != 'ID') {
                    $this->output->error('Invalid field reference: ' . $encryptedId);
                }

                continue;
            }

            $this->handleFieldValue($encryptedId, $fieldReference, $row);
        }
    }

    private function handleFieldValue(string $encryptedId, FieldReference $fieldReference, Collection $row): void
    {
        if(!$fieldReference->isRepeatField()) {
            if($fieldReference->isLocalized() && $this->locale === FieldLine::NON_LOCALIZED) {
                return;
            }

            if(! $fieldReference->isLocalized() && $this->locale !== FieldLine::NON_LOCALIZED) {
                return;
            }
        }

        // Column for import
        $value = $row[$this->columnIndex];

        $currentValue = $fieldReference->getValue($this->locale);

        // If it's the same value as original value, skip the import for this value
        // Currently all repeat values will get processed every time because the value does not ever match the entire current value.
        if($value == $currentValue) {
            return;
        }

        // TODO: in wizard mode, we should present the new value vs the original value to the admin.
        $fieldReference->saveValue($value, $this->locale);

        if($this->output) {
            $this->output->info('Imported value for ' . decrypt($encryptedId) . ' (' . $this->locale . ')');
            $this->output->writeln('Old value: ' . print_r($currentValue, true));
            $this->output->writeln('New value: ' . print_r($value, true));
        }
    }

    public function setOutput(OutputStyle $output): static
    {
        $this->output = $output;

        return $this;
    }
}
