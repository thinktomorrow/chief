<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use SplFileInfo;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Shared\Helpers\Form;
use Thinktomorrow\Chief\Forms\Fields\Common\LocalizedFormKey;

class SaveFields
{
    /**
     * Default method to save Field values for Eloquent models. This provides
     * support for regular columns, localized ones and dynamic attributes.
     */
    public function save(Model $model, Fields $fields, array $input, array $files): void
    {
        [$input, $files] = $this->removeDuplicateFilePayload($input, $files);

        /** @var Field $field */
        foreach ($fields->all() as $field) {
            // Custom save of the values
            if ($field->hasSave()) {
                continue;
            }

            // Custom set of the model attribute values
            if ($field->hasSetModelValue()) {
                call_user_func_array($field->getSetModelValue(), [$model, $field, $input, $files]);

                continue;
            }

            // Set standard non-localized attribute on the model or handle this
            if (! $field->hasLocales()) {
                $value = $field->hasPrepareModelValue()
                    ? call_user_func_array($field->getPrepareModelValue(), [data_get($input, $field->getKey()), $input])
                    : data_get($input, $field->getKey());

                $model->{$field->getColumnName()} = $value;

                continue;
            }

             if($field->getLocalizedFormKeyTemplate() == ':name.:locale') {
                foreach(data_get($input, $field->getColumnName()) as $locale => $value){
                    $this->localizedValueCallable($model, $field, $input)($locale, $field->getColumnName(), $value);
                }
            } else {
                // Dynamic localized values or standard translated
                // For standard translations we set value with the colon notation, e.g. title:en
                Form::foreachTrans(data_get($input, 'trans', []), $this->localizedValueCallable($model, $field, $input));
            }


        }

        $model->save();

        // Custom save methods
        foreach ($fields->all() as $field) {
            if ($field->hasSave()) {
                call_user_func_array($field->getSave(), [$model, $field, $input, $files]);
            }
        }
    }

    private function localizedValueCallable($model, $field, $input): \Closure
    {
        return function ($locale, $key, $value) use ($model, $field, $input) {
            if ($key !== $field->getColumnName()) {
                return;
            }

            $value = $field->hasPrepareModelValue()
                ? call_user_func_array($field->getPrepareModelValue(), [$value, $input, $locale])
                : $value;

            if ($this->isFieldForDynamicValue($model, $field)) {
                $model->setDynamic($key, $value, $locale);
            } else {
                $model->{$field->getColumnName() . ':' . $locale} = $value;
            }
        };
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

    private function isFieldForDynamicValue(Model $model, Field $field): bool
    {
        return method_exists($model, 'isDynamic') && $model->isDynamic($field->getColumnName());
    }

    private function isValidFile($file): bool
    {
        return $file instanceof SplFileInfo && '' !== $file->getPath();
    }
}
