<?php

namespace Thinktomorrow\Chief\Management;

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

    public function validate(ModelManager $manager, Request $request)
    {
        // Merge all fields and their validation rules....
        list('rules' => $rules, 'messages' => $messages, 'customAttributes' => $customAttributes) = $this->mergeValidations($manager);

        return $this->performValidation($request->all(), $rules, $messages, $customAttributes);
    }

    private function mergeValidations(ModelManager $manager)
    {
        $validation = ['rules' => [], 'messages' => [], 'customAttributes' => []];

        foreach ($manager->fields() as $field) {
            if (! $field->hasValidation()) {
                continue;
            }

            foreach (array_keys($validation) as $key) {
                $validation[$key] = array_merge($validation[$key], $field->validation[$key]);
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
