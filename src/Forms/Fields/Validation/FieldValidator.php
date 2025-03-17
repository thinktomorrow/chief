<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation;

use Illuminate\Contracts\Validation\Factory;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedField;

class FieldValidator
{
    /** @var Factory */
    private $validatorFactory;

    public function __construct(Factory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    public function handle(Fields $fields, array $payload): void
    {
        /** @var Validatable & LocalizedField $field */
        foreach ($fields->all() as $field) {
            if ($field->hasValidation()) {
                $field->createValidatorInstance($this->validatorFactory, $payload)
                    ->validate();
            }
        }
    }
    //
    //
    //    private function createFileValidator(Fields\File $field, array $payload): Validator
    //    {
    //        // Validate uploaded File first
    //        if ($field->hasValidation()) {
    //
    //            $payload = [];
    //
    //            foreach (array_keys($validationParameters->getRules()) as $fieldKey) {
    //                $payload[$fieldKey] =
    //                }
    //
    //            $validationParameters = ValidationParameters::make($field);
    //            dd(
    //
    //                $validationParameters->getRules(),
    //                $validationParameters->getMessages(),
    //                $validationParameters->getAttributes(),
    //            );
    //            $validator = \Illuminate\Support\Facades\Validator::make([$field->getKey() => [$uploadedFile]], [$field->getKey() => $field->getRules()]);
    //            $validator->validate();
    // //        }
    //        }
    //
    //
    //        /*
    //         * Complete rule definitions - in the format of [attribute => rules] - will be left as is and are not being manipulated e.g. ['foobar' => 'required']
    //         * Otherwise if the rules are being passed as an array, they will be normalized to a string.
    //         */
    //        private function ruleMatrix(array $keys, array $values): array
    //        {
    //            if (is_string(key($values))) {
    //                return $values;
    //            }
    //
    //            return array_fill_keys($keys, $values);
    //        }
    //
    //        private function matrix(array $keys, array $values): array
    //        {
    //            if (empty($values)) {
    //                return [];
    //            }
    //
    //            return array_fill_keys($keys, reset($values));
    //        }

    //
    //    /*
    //     * Complete rule definitions - in the format of [attribute => rules] - will be left as is and are not being manipulated e.g. ['foobar' => 'required']
    //     * Otherwise if the rules are being passed as an array, they will be normalized to a string.
    //     */
    //    private function ruleMatrix(array $keys, array $values): array
    //    {
    //        if (is_string(key($values))) {
    //            return $values;
    //        }
    //
    //        return array_fill_keys($keys, $values);
    //    }
    //
    //    private function matrix(array $keys, array $values): array
    //    {
    //        if (empty($values)) {
    //            return [];
    //        }
    //
    //        return array_fill_keys($keys, reset($values));
    //    }
}
