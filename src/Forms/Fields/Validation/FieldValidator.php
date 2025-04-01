<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation;

use Illuminate\Contracts\Validation\Factory;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
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
}
