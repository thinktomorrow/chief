<?php

namespace Thinktomorrow\Chief\Fields\Validators;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\Factory;
use Thinktomorrow\Chief\Fields\LocalizedFieldValidationRules;
use Thinktomorrow\Chief\Fields\Types\Field;

class FieldValidatorFactory
{
    /** @var Factory */
    private $validatorFactory;

    public function __construct(Factory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    public function create(Field $field, array $data): Validator
    {
        if (!$field->hasValidation()) {
            return new NullValidator();
        }

        if ($field->validation instanceof Validator) {
            return $field->validation;
        }

        if ($field->validation instanceof \Closure) {
            $closure = $field->validation;
            return $closure($this->validatorFactory, $data);
        }

        return $this->composeValidatorFromRules($field, $data);
    }

    private function composeValidatorFromRules(Field $field, array $data): Validator
    {
        if (!is_array($field->validation) || !isset($field->validation[0])) {
            throw new \Exception('Invalid validation given. Rules should be passed as non-associative array.');
        }
        
        return $this->validatorFactory->make($data,
            $this->normalizeRules($field, $field->validation[0], $data),
            $field->validation[1] ?? [], // messages
            $field->validation[2] ?? [] // custom attributes
        );
    }

    /**
     * @param Field $field
     * @param string|array $rules
     * @param array $data
     * @return array|null
     */
    private function normalizeRules(Field $field, $rules, array $data)
    {
        // Normalize rules: If no attribute is passed for the rule, we use the field name.
        if (!is_array($rules) || isset($rules[0])) {
            $rules = [$field->name => (is_array($rules) ? reset($rules) : $rules)];

            if ($field->isTranslatable()) {
                $rules = (new LocalizedFieldValidationRules($field->locales))
                    ->influenceByPayload($data)
                    ->rules($rules);
            }
        }

        return $rules;
    }
}
