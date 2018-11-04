<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Details;

/**
 * Class ModelDetails
 *
 * @property $key
 * @property $singular
 * @property $plural
 * @property $internal_label
 * @property $title
 * @property $subtitle
 * @property $intro
 */
class ManagedModelDetails
{
    /** @var array */
    protected $values = [];

    public function __construct(string $key, string $singular, string $plural, string $internal_label, string $title, string $subtitle, string $intro)
    {
        // Default model details
        $this->values['key'] = $key;
        $this->values['singular'] = $singular;
        $this->values['plural'] = $plural;
        $this->values['internal_label'] = $internal_label;

        // Manager model details
        $this->values['title'] = $title;
        $this->values['subtitle'] = $subtitle;
        $this->values['intro'] = $intro;
    }

    public function get($attribute = null)
    {
        if (array_key_exists($attribute, $this->values)) {
            return $this->values[$attribute];
        }

        return null;
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
        return (string) $this->get('key');
    }
}
