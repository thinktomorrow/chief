<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Common\Localizable;

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
        /** @var Validatable & Localizable $field */
        foreach ($fields->all() as $field) {
            if ($field->hasValidation()) {
                $this->createValidator($field, $payload)->validate();
            }
        }
    }

    private function createValidator(Validatable & Localizable $field, array $payload): Validator
    {
        // TODO: what about the files.*.detach stuff...
        // return ValidationNames::fromFormat($this->getValidationNameFormat())
        //            ->payload($payload)
        //            ->replace('locale', $this->getLocales())
        //            ->replace('name', [$this->getName()])
        //            ->removeKeysContaining(['files.*.detach', 'images.*.detach'])
        //            ->get()

//        trap()

        // Rename to validationParameters
        $validationParameters = ValidationParameters::make($field);

        return $this->validatorFactory->make(
            $payload,
            $validationParameters->getRules(),
            $validationParameters->getMessages(),
            $validationParameters->getAttributes(),
        );

//        return $this->validatorFactory->make(
//            $payload,
//            $this->ruleMatrix($field->getValidationNames($payload), $field->getValidationParameters()->getRules()), // rules
//            $this->matrix($field->getValidationNames($payload), $field->getValidationParameters()->getMessages()), // messages
//            $this->matrix($field->getValidationNames($payload), $field->getValidationParameters()->getAttributes()) // attributes
//        );
    }

    /*
     * Complete rule definitions - in the format of [attribute => rules] - will be left as is and are not being manipulated e.g. ['foobar' => 'required']
     * Otherwise if the rules are being passed as an array, they will be normalized to a string.
     */
    private function ruleMatrix(array $keys, array $values): array
    {
        if (is_string(key($values))) {
            return $values;
        }

        return array_fill_keys($keys, $values);
    }

    private function matrix(array $keys, array $values): array
    {
        if (empty($values)) {
            return [];
        }

        return array_fill_keys($keys, reset($values));
    }
}
