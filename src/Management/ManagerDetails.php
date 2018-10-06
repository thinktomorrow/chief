<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ManagerDetails
 * @property $key
 * @property $class
 * @property $singular
 * @property $plural
 * @property $slug
 */
class ManagerDetails implements Arrayable
{
    /** @var array */
    private $values = [];

    public function __construct(string $key, string $class, string $singular, string $plural)
    {
        $this->values['key'] = $key;
        $this->values['class'] = $class;
        $this->values['singular'] = $singular;
        $this->values['plural'] = $plural;
    }

    public function all(): array
    {
        return $this->values;
    }

    public function get($attribute = null)
    {
        if (array_key_exists($attribute, $this->values)) {
            return $this->values[$attribute];
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
