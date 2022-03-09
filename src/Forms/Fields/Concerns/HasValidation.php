<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;

trait HasValidation
{
    protected bool $isRequired = false;

    protected array $rules = [];
    protected ?string $validationAttribute = null;
    protected array $validationMessages = [];

    public function hasValidation(): bool
    {
        return count($this->rules) > 0 || $this->isRequired();
    }

    public function required(bool $flag = true): static
    {
        $this->isRequired = $flag;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired || $this->hasDefinitionInRules('required', FallbackLocaleRequiredRule::RULE);
    }

    public function rules(string|array $rules): static
    {
        if (is_array($rules) && $this->isAlreadyKeyed($rules)) {
            throw new \InvalidArgumentException('Validation rules should be declared without a key. Keys are automatically added.');
        }

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $this->rules = (array) $rules;

        return $this;
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

    private function isAlreadyKeyed(array $value): bool
    {
        return ! array_is_list($value);
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
