<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Database\Eloquent\Model;

/**
 * Composite key consisting of the type of class combined with the
 * model id. Both are joined with an @ symbol. This is used as
 * identifier of the relation mostly as form values.
 *
 * @return CollectionId
 */
class CollectionId
{
    /** @var string */
    private $className;

    /** @var int */
    private $id;

    public function __construct(string $className, int $id)
    {
        $this->className = $className;
        $this->id = $id;
    }

    public static function fromString(string $collectionId): self
    {
        if(false == strpos($collectionId, '@')) {
            throw new \InvalidArgumentException('Invalid collection id. Composite key should honour schema <class>@<id>. [' . $collectionId . '] was passed instead.');
        }

        list($className, $id) = explode('@', $collectionId);

        return new static($className, (int) $id);
    }

    /**
     * Recreate the model instance that is referred to by this collection id
     * @return Model
     */
    public function instance(): Model
    {
        return (new $this->className)->findOrFail($this->id);
    }

    public function get(): string
    {
        return $this->className.'@'.$this->id;
    }

    public function equals($other): bool
    {
        return (get_class($this) === get_class($other) && $this->get() === $other->get());
    }

    public function __toString()
    {
        return $this->get();
    }
}