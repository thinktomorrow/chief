<?php

namespace Thinktomorrow\Chief\Forms\Fields\Validation;

interface Validatable
{
    public function getName(): string;
    public function getLabel(): ?string;

    public function hasValidation(): bool;

    public function isRequired(): bool;
    public function rules(array $rules): static;
    public function getRules(): array;

    public function validationAttribute(string $validationAttribute): static;
    public function getValidationAttribute(): ?string;

    public function validationMessages(array $validationMessages): static;
    public function getValidationMessages(): array;
}
