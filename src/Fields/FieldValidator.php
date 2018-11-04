<?php

namespace Thinktomorrow\Chief\Fields;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;

class FieldValidator
{
    /** @var Factory */
    private $validator;

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Fields $fields, Request $request)
    {
        // Merge all fields and their validation rules....
        list('rules' => $rules, 'messages' => $messages, 'customAttributes' => $customAttributes) = $this->mergeValidations($fields, $request);

        return $this->performValidation($request->all(), $rules, $messages, $customAttributes);
    }

    private function mergeValidations(Fields $fields, Request $request)
    {
        $validation = ['rules' => [], 'messages' => [], 'customAttributes' => []];

        foreach ($fields as $field) {
            if (! $field->hasValidation()) {
                continue;
            }

            $fieldValidation = $field->getValidation($request->all());

            foreach (array_keys($validation) as $key) {
                $validation[$key] = array_merge($validation[$key], $fieldValidation[$key]);
            }
        }

        return $validation;
    }

    private function performValidation(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->validator->make($data, $rules, $messages, $customAttributes)
                        ->validate();
    }
}
