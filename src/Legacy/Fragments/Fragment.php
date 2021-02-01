<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Legacy\Fragments;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;

class Fragment
{
    /** @var string */
    private $key;

    /** @var array */
    private $values;

    /** @var Fields */
    private $fields;

    /** @var null|int */
    private $modelId;

    /** @var null|string */
    private $modelIdInputName;

    private function __construct(string $key, array $values, Fields $fields, int $modelId = null, string $modelIdInputName = null)
    {
        $this->key = $key;
        $this->values = $values;
        $this->modelId = $modelId;
        $this->fields = $fields;
        $this->modelIdInputName = $modelIdInputName;
    }

    public static function fromModel(FragmentModel $fragmentModel): self
    {
        return new static($fragmentModel->key, $fragmentModel->values->all(), new Fields(), (int) $fragmentModel->id);
    }

    public static function fromNew(string $key, array $values): self
    {
        return new static($key, $values, new Fields());
    }

    public static function empty(string $key): self
    {
        return static::fromNew($key, []);
    }

    public static function fromRequestPayload(string $key, array $payload): self
    {
        if (!isset($payload['modelId'])) {
            return static::fromNew($key, $payload);
        }

        $modelId = $payload['modelId'];
        unset($payload['modelId']);

        return new static($key, $payload, new Fields(), (int) $modelId);
    }

    public function setFields(Fields $fields): self
    {
        return new static($this->key, $this->values, $fields, $this->modelId, $this->modelIdInputName);
    }


    /** @return string */
    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(string $key, ?string $locale = null)
    {
        if (!isset($this->values[$key])) {
            return null;
        }

        // When the value is an array, it is assumed that this is an array of locales, each representing the value for that locale
        if (is_array($this->values[$key])) {
            if ($locale && array_key_exists($locale, $this->values[$key])) {
                return $this->values[$key][$locale];
            }
        }

        return $this->values[$key];
    }

    /** @return array */
    public function getValues(): array
    {
        return $this->values;
    }

    public function getModelId(): int
    {
        return $this->modelId;
    }

    public function hasModelId(): bool
    {
        return !is_null($this->modelId);
    }

    public function getFields(): Fields
    {
        return $this->fields;
    }

    public function setModelIdInputName(string $modelIdInputName): self
    {
        return new static($this->key, $this->values, $this->fields, $this->modelId, $modelIdInputName);
    }

    public function getModelIdInputName(): ?string
    {
        return $this->modelIdInputName;
    }
}
