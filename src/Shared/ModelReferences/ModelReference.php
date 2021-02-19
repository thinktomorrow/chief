<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\ModelReferences;

final class ModelReference
{
    private string $className;
    protected string $id;

    public function __construct(string $className, string $id)
    {
        $this->className = $className;
        $this->id = $id;
    }

    public static function fromStatic(string $className): self
    {
        return new static($className, "0");
    }

    public static function fromString(string $reference): self
    {
        if (false == strpos($reference, '@')) {
            throw new \InvalidArgumentException('Invalid reference composition. A model reference should consist of schema <class>@<id>. [' . $reference . '] was passed instead.');
        }

        list($className, $id) = explode('@', $reference);

        if ("" === $id) {
            throw new \InvalidArgumentException('Missing id on model reference. [' . $reference . '] was passed.');
        }

        return new static($className, (string) $id);
    }

    /**
     * Recreate the model instance that is referred to.
     * By default we assume an eloquent model. If the id is 0
     * the reference points to a model object instead
     */
    public function instance(array $attributes = [])
    {
        $className = $this->className();

        if ($this->id === "0") {
            return new $className($attributes);
        }

        $model = $className::withoutGlobalScopes()->findOrFail($this->id);
        $model->fill($attributes);

        return $model;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function className(): string
    {
        return $this->className;
    }

    public function get(): string
    {
        return "$this->className@$this->id";
    }

    public function equals($other): bool
    {
        return (get_class($this) === get_class($other) && $this->get() === $other->get());
    }

    public function is(string $modelReferenceString): bool
    {
        return $this->get() === $modelReferenceString;
    }

    public function __toString(): string
    {
        return $this->get();
    }
}
