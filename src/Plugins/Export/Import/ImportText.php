<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Thinktomorrow\Squanto\Database\DatabaseLine;
use Thinktomorrow\Squanto\Domain\Exceptions\InvalidLineKeyException;
use Thinktomorrow\Squanto\Domain\LineKey;

class ImportText implements ToCollection
{
    private string $idIndex;

    private string $locale;

    private string $columnIndex;

    private ?OutputStyle $output = null;

    // Update the translations even if they are the same
    private bool $forceUpdate = true;

    public function __construct(string $idIndex, string $columnIndex, string $locale)
    {
        $this->idIndex = $idIndex;
        $this->columnIndex = $columnIndex;
        $this->locale = $locale;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Ignore empty rows or invalid ID references
            if (! isset($row[$this->idIndex]) || $row[$this->idIndex] == 'ID') {
                continue;
            }

            $encryptedId = $row[$this->idIndex];

            try {
                $lineKey = LineKey::fromString(decrypt($encryptedId));
            } catch (DecryptException|InvalidLineKeyException $e) {
                $this->writeToOutput('Invalid squanto id reference: '.$encryptedId, 'error');

                continue;
            }

            $value = $row[$this->columnIndex];

            if ($line = DatabaseLine::findByKey($lineKey)) {
                $values = json_decode($line->values, true);
                $previousValue = $values['value'][$this->locale] ?? null;

                // Typecast to string because this is what squanto expects
                $value = is_null($value) ? null : (string) $value;

                if (! $this->forceUpdate && $previousValue === $value) {
                    continue;
                }

                $values['value'][$this->locale] = $value;

                $line->update(['values' => $values]);
                $this->writeToOutput('Updated translation for key: '.$lineKey->get());
                $this->writeToOutput('Previous value: '.$previousValue);
                $this->writeToOutput('New value: '.$value);
            } else {
                // TODO: we could also opt for insert here...
                $this->writeToOutput('No line found for key: '.$lineKey->get(), 'warn');
            }
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
