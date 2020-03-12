<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\DynamicAttributes;

class DynamicAttributes
{
    /** @var array */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function has(string $key): bool
    {
        return ($this->get($key, '__NOTFOUND__') !== '__NOTFOUND__');
    }

    public function get(string $key, $default = null)
    {
        return data_get($this->values, $key, $default);
    }

    public function set(string $key, $value)
    {
        data_set($this->values, $key, $value);
    }

    public function all()
    {
        return $this->values;
    }
}
