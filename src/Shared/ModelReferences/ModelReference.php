<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\ModelReferences;

use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Wireable;

final class ModelReference implements Wireable
{
    private string $className;

    protected string $id;

    /**
     * The key used to identify an unidentified model reference which can
     * be present when the model is not yet persisted
     */
    private static $unidentiefKey = 'UNIDENTIFIED';

    private static $staticKey = 'STATIC';

    private function __construct(string $className, string $id)
    {
        $this->validateParameters($className, $id);

        $this->className = $className;
        $this->id = $id;
    }

    public static function fromStatic(string $className): self
    {
        return new self(self::convertToFullClass($className), self::$staticKey);
    }

    public static function fromUnidentified(string $className): self
    {
        return new self(self::convertToFullClass($className), self::$unidentiefKey);
    }

    public static function make(string $className, $id): self
    {
        if (! $id) {
            return self::fromUnidentified($className);
        }

        return new self(self::convertToFullClass($className), (string) $id);
    }

    public static function fromString(string $reference): self
    {
        if (strpos($reference, '@') == false) {
            throw new \InvalidArgumentException('Invalid reference composition. A model reference should consist of schema <class>@<id>. ['.$reference.'] was passed instead.');
        }

        [$className, $id] = explode('@', $reference);

        if ($id === '') {
            return self::fromUnidentified($className);
        }

        return new self(self::convertToFullClass($className), $id);
    }

    /**
     * Recreate the model instance that is referred to.
     * By default we assume an eloquent model. If the id is 0
     * the reference points to a model object instead
     *
     * @return mixed
     */
    public function instance(array $attributes = [])
    {
        $className = $this->className();

        if (! class_exists($className)) {
            throw new CannotInstantiateModelReference('['.$className.'] does not exist as a class.');
        }

        if (! $this->hasValidId() || $this->isStatic() || $this->isUnidentified()) {
            return $this->object($attributes);
        }

        $model = $className::withoutGlobalScopes()->find($this->id);

        if (! $model) {
            throw new CannotInstantiateModelReference('['.$className.'] with id ['.$this->id.'] does not exist.');
        }

        $model->fill($attributes);

        return $model;
    }

    /**
     * Recreate a model instance without database touching
     * By default we assume an eloquent model.
     *
     * @return mixed
     */
    public function object(array $attributes = [])
    {
        $className = $this->className();

        if (! class_exists($className)) {
            throw new CannotInstantiateModelReference('['.$className.'] does not exist as a class.');
        }

        $model = app()->make($className, $attributes);

        if ($this->hasValidId()) {
            $model->{$model->getKeyName()} = $this->id;
        }

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

    public function shortClassName(): string
    {
        return self::convertToMorphedClass($this->className);
    }

    public function get(): string
    {
        if ($this->isUnidentified() || $this->isStatic()) {
            return "$this->className@";
        }

        return "$this->className@$this->id";
    }

    public function getShort(): string
    {
        $className = $this->shortClassName();

        return "$className@$this->id";
    }

    public function equals($other): bool
    {
        return get_class($this) === get_class($other) && $this->get() === $other->get();
    }

    public function is(string $modelReferenceString): bool
    {
        return $this->get() === $modelReferenceString || $this->getShort() === $modelReferenceString;
    }

    public function isUnidentified(): bool
    {
        return $this->id === self::$unidentiefKey;
    }

    public function isStatic(): bool
    {
        return $this->id === self::$staticKey;
    }

    private function hasValidId(): bool
    {
        if ($this->isUnidentified() || $this->isStatic()) {
            return false;
        }

        return (bool) $this->id;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    private static function convertToFullClass(string $className): string
    {
        return Relation::getMorphedModel($className) ?? $className;
    }

    private static function convertToMorphedClass(string $className): string
    {
        if ($morphedModelKey = array_search($className, Relation::$morphMap)) {
            return $morphedModelKey;
        }

        return $className;
    }

    private function validateParameters(string $className, string $id)
    {
        if (! $className) {
            throw new InvalidModelReference('['.$className.'@'.$id.'] is missing a valid class reference.');
        }

        if (! trim($id)) {
            throw new InvalidModelReference('['.$className.'@'.$id.'] is missing a valid id reference.');
        }
    }

    public function toLivewire()
    {
        return ['model-reference' => $this->get()];
    }

    public static function fromLivewire($value)
    {
        return self::fromString($value['model-reference']);
    }
}
