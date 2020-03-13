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

    public static function fromRawValue($value): self
    {
        $value = is_null($value) ? [] : (is_array($value) ? $value : json_decode($value, true));

        return new static((array) $value);
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

    public function toJson(): string
    {
        return json_encode($this->all());
    }
}
