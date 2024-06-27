<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ImportLines implements ToCollection
{
    private string $fieldReferenceColumnIndex;
    private string $locale;
    private string $columnIndex;

    public function __construct(string $fieldReferenceColumnIndex, string $columnIndex, string $locale)
    {
        $this->fieldReferenceColumnIndex = $fieldReferenceColumnIndex;
        $this->columnIndex = $columnIndex;
        $this->locale = $locale;
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row) {

            // Ignore empty rows or invalid ID references
            if(! isset($row[$this->fieldReferenceColumnIndex])) {
                continue;
            }

            try{
                $fieldReference = FieldReference::fromEncryptedKey($row[$this->fieldReferenceColumnIndex]);
            } catch (DecryptException|ModelNotFoundException $e) {
                continue;
            }

            // Column for import
            $value = $row[$this->columnIndex];

            // If it's the same value as original value, skip the import for this value
            if($value == $fieldReference->getValue($this->locale)) continue;

            // TODO: in wizard mode, we should present the new value vs the original value to the admin.

            $fieldReference->saveValue($value, $this->locale);

        }
    }

    private function getModelAndField($encryptedKey): bool|array
    {
        try {
            $decryptedKey = decrypt($encryptedKey);

            [$modelReference, $fieldKey] = explode('|', $decryptedKey);

            $model = ModelReference::fromString($modelReference)->instance();
            $resource = $this->registry->findResourceByModel($model::class);

            return [
                $model,
                $resource->field($model, $fieldKey),
                $resource,
            ];

        } catch (DecryptException|ModelNotFoundException $e) {
            return false;
        }
    }
}
