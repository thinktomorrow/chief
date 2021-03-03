<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Validation;

class ValidationParameters
{
    private array $rules;
    private array $customMessages;
    private array $customAttributes;

    public function __construct($rules, array $customMessages, array $customAttributes)
    {
        $this->rules = ValidationParameters::normalizeToArray($rules);
        $this->customMessages = $customMessages;
        $this->customAttributes = $customAttributes;
    }

    public function isEmpty(): bool
    {
        return count($this->rules) < 1;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getMessages(): array
    {
        return $this->customMessages;
    }

    public function getAttributes(): array
    {
        return $this->customAttributes;
    }

    public function customizeRules(array $customRules): self
    {
        $rules = $this->rules;

        foreach ($rules as $k => $rule) {
            $params = '';

            // Split up the rule and any parameters
            if (false !== strpos($rule, ':')) {
                list($rule, $params) = explode(':', $rule);
            }

            if (isset($customRules[$rule])) {
                $rules[$k] = $customRules[$rule] . ($params ? ':' . $params : '');
            }
        }

        return new static($rules, $this->getMessages(), $this->getAttributes());
    }

    /*
     * Rules can be passed as array of rules or pipe delimited string
     */
    private static function normalizeToArray($values): array
    {
        if (is_string($values)) {
            $values = explode('|', $values);
        }

        return $values;
    }
}
