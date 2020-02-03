<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Validation;

use Thinktomorrow\Chief\Fields\Types\Field;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\Factory;

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

        if ($field->getValidation() instanceof Validator) {
            return $field->getValidation();
        }

        if ($field->getValidation() instanceof \Closure) {
            $closure = $field->getValidation();
            return $closure($this->validatorFactory, $data);
        }

        return $this->composeValidatorFromRules($field, $data);
    }

    private function composeValidatorFromRules(Field $field, array $data): Validator
    {
        $validation = $field->getValidation();

        return $this->validatorFactory->make($data,
            $this->sanitizeValidationValues($field, $validation[0] ?? [], $data), // rules
            $this->sanitizeValidationValues($field, $validation[1] ?? [], $data), // messages
            $this->sanitizeValidationValues($field, $validation[2] ?? [], $data) // attributes
        );
    }

    /**
     * @param Field $field
     * @param string|array $values
     * @param array $payload
     * @return array|null
     */
    private function sanitizeValidationValues(Field $field, $values, array $payload): array
    {
        // Complete rule definitions - in the format of [attribute => rules] - will be left as is and are not being manipulated e.g. ['foobar' => 'required']
        // Otherwise if the rules are being passed as an array, they will be normalized to a string.
        if(is_array($values)){
            if(is_string(key($values))) {
                return $values;
            }

            $values = reset($values);
        }

        return array_fill_keys( $field->getValidationNames($payload) , $values);
    }
}
