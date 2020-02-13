<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Details;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @property $key
 * @property $singular
 * @property $plural
 * @property $internal_label
 * @property $title
 * @property $subtitle
 * @property $intro
 */
class Details implements Arrayable
{
    /** @var array */
    protected $values = [];

    public function __construct($id, string $key, string $singular, string $plural, string $internal_label, string $title)
    {
        // Default model details
        $this->values['id'] = $id;
        $this->values['key'] = $key;
        $this->values['singular'] = $singular;
        $this->values['plural'] = $plural;
        $this->values['internal_label'] = $internal_label;
        $this->values['title'] = $title;
    }

    public function get($attribute = null)
    {
        if (array_key_exists($attribute, $this->values)) {
            return $this->values[$attribute];
        }

        return null;
    }

    public function has($attribute): bool
    {
        return null !== $this->get($attribute);
    }

    public function set($attribute, $value)
    {
        $this->values[$attribute] = $value;

        return $this;
    }

    public function __get($attribute)
    {
        return $this->get($attribute);
    }

    public function __set($attribute, $value)
    {
        return $this->set($attribute, $value);
    }

    public function __toString()
    {
        return (string)$this->get('key');
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->values;
    }
}
