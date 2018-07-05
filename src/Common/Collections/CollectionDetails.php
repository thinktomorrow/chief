<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\Collections;

/**
 * Class CollectionDetails
 * @property $key
 * @property $className
 * @property $singular
 * @property $plural
 * @property $internal_label
 */
class CollectionDetails
{
    /** @var string */
    private $key;

    /** @var string */
    private $className;

    /** @var string */
    private $singular;

    /** @var string */
    private $plural;

    /** @var string */
    private $internal_label;

    public function __construct(string $key, string $className, string $singular, string $plural, string $internal_label)
    {
        $this->key = $key;
        $this->className = $className;
        $this->singular = $singular;
        $this->plural = $plural;
        $this->internal_label = $internal_label;
    }

    public function all(): array
    {
        return [
            'key'            => $this->key,
            'class'          => $this->className,
            'singular'       => $this->singular,
            'plural'         => $this->plural,
            'internal_label' => $this->internal_label,
        ];
    }

    public function get($attribute = null)
    {
        if (property_exists($this, $attribute)) {
            return $this->$attribute;
        }

        return null;
    }

    public function __get($attribute)
    {
        return $this->get($attribute);
    }

    public function __toString()
    {
        return (string)$this->get('key');
    }

    public function toArray()
    {
        return $this->all();
    }
}