<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SplFileInfo;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;
use Thinktomorrow\Chief\ManagedModels\Media\Application\FileFieldHandler;
use Thinktomorrow\Chief\ManagedModels\Media\Application\ImageFieldHandler;
use Thinktomorrow\Chief\Shared\Helpers\Form;

trait SavingFields
{
    /**
     * Default method to save Field values for Eloquent models. This provides
     * support for regular columns, localized ones and dynamic attributes.
     *
     * @param Fields $fields
     * @param array $input
     * @param array $files
     */
    public function saveFields(Fields $fields, array $input, array $files): void
    {
        [$input, $files] = $this->removeDuplicateFilePayload($input, $files);

        foreach ($fields as $field) {
            if ($this->detectCustomSaveMethod($field)) {
                continue;
            }

            if (! $field->isLocalized()) {
                // Set standard non-localized attribute on the model
                ($customSetMethod = $this->detectCustomSetMethod($field))
                    ? $this->$customSetMethod($field, $input)
                    : $this->{$field->getColumn()} = data_get($input, $field->getKey());

                continue;
            }

            // Dynamic localized values or standard translated
            // For standard translations we set value with the colon notation, e.g. title:en
            Form::foreachTrans(data_get($input, 'trans', []), function ($locale, $key, $value) use ($field) {
                if ($key !== $field->getColumn()) {
                    return;
                }

                if ($this->isFieldForDynamicValue($field)) {
                    $this->setDynamic($key, $value, $locale);
                } else {
                    $this->{$field->getColumn().':'.$locale} = $value;
                }
            });
        }

        $this->save();

        // Custom save methods
        foreach ($fields as $field) {
            if ($customSaveMethod = $this->detectCustomSaveMethod($field)) {
                $this->$customSaveMethod($field, $input, $files);
            }
        }
    }

    private function isFieldForDynamicValue(Field $field): bool
    {
        return (method_exists($this, 'isDynamic') && $this->isDynamic($field->getColumn()));
    }

    private function detectCustomSaveMethod(Field $field): ?string
    {
        $saveMethodByKey = 'save' . ucfirst(Str::camel($field->getKey())) . 'Field';
        $saveMethodByType = 'save' . ucfirst(Str::camel($field->getType()->get())) . 'Fields';

        foreach ([$saveMethodByKey, $saveMethodByType] as $saveMethod) {
            if (method_exists($this, $saveMethod)) {
                return $saveMethod;
            }
        }

        return null;
    }

    private function detectCustomSetMethod(Field $field): ?string
    {
        $methodName = 'set' . ucfirst(Str::camel($field->getKey())) . 'Field';

        return (method_exists($this, $methodName)) ? $methodName : null;
    }

    private function saveFileFields(FileField $field, array $input, array $files): void
    {
        app(FileFieldHandler::class)->handle($this, $field, $input, $files);
    }

    private function saveImageFields(ImageField $field, array $input, array $files): void
    {
        app(ImageFieldHandler::class)->handle($this, $field, $input, $files);
    }

    private function removeDuplicateFilePayload($input, $files): array
    {
        $flatInput = Arr::dot($input);
        $flatFiles = Arr::dot($files);

        foreach ($flatInput as $key => $entry) {
            if ($this->isValidFile($entry) && array_key_exists($key, $flatFiles)) {
                Arr::forget($input, $key);
            }
        }

        return [$input, $files];
    }

    protected function isValidFile($file): bool
    {
        return $file instanceof SplFileInfo && $file->getPath() !== '';
    }
}
