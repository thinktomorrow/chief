<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Factory;
use InvalidArgumentException;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;

trait HasValidation
{
    protected bool $isRequired = false;

    protected array $rules = [];
    protected ?string $validationAttribute = null;
    protected array $validationMessages = [];

    public function required(bool $flag = true): static
    {
        $this->isRequired = $flag;

        return $this;
    }

    public function requiredFallbackLocale(): static
    {
        return $this->rules(FallbackLocaleRequiredRule::RULE);
    }

    public function rules(string|array|Rule $rules): static
    {
        if (is_array($rules) && $this->isAlreadyKeyed($rules)) {
            throw new InvalidArgumentException('Validation rules should be declared without a key. Keys are automatically added.');
        }

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $this->rules = array_merge($this->rules, (array)$rules);

        return $this;
    }

    private function isAlreadyKeyed(array $value): bool
    {
        return ! array_is_list($value);
    }

    public function validationAttribute(string $validationAttribute): static
    {
        $this->validationAttribute = $validationAttribute;

        return $this;
    }

    public function getValidationAttribute(): ?string
    {
        return $this->validationAttribute;
    }

    public function validationMessages(array $validationMessages): static
    {
        $this->validationMessages = $validationMessages;

        return $this;
    }

    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }

    public function createValidatorInstance(Factory $validatorFactory, array $payload): Validator
    {
        $validationParameters = ValidationParameters::make($this);

        return $validatorFactory->make(
            $payload,
            $validationParameters->getRules(),
            $validationParameters->getMessages(),
            $validationParameters->getAttributes(),
        );
    }

    public function getRules(): array
    {
        if (! $this->hasValidation()) {
            return [];
        }

        $rules = ! $this->hasDefinitionInRules('required', 'nullable')
            ? [$this->isRequired ? 'required' : 'nullable']
            : [];

        return array_merge($rules, $this->rules);
    }

    public function hasValidation(): bool
    {
        return count($this->rules) > 0 || $this->isRequired();
    }

    public function isRequired(): bool
    {
        if (app()->environment('local') && true === config('chief.disable_field_required_validation')) {
            return false;
        }

        return $this->isRequired || $this->hasDefinitionInRules('required', FallbackLocaleRequiredRule::RULE);
    }

    private function hasDefinitionInRules(string ...$definitions): bool
    {
        foreach ($definitions as $definition) {
            if (false !== array_search($definition, $this->rules)) {
                return true;
            }
        }

        return false;
    }
}
