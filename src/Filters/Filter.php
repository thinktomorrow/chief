<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Filters;

use Closure;
use Thinktomorrow\Chief\Filters\Types\FilterType;

abstract class Filter
{
    /** @var FilterType */
    private $filterType;

    protected $values = [];

    final public function __construct(FilterType $filterType, string $key)
    {
        $this->filterType = $filterType;

        $this->values['key'] = $this->values['name'] = $this->values['label'] = $key;
        $this->values['type'] = $filterType->get();
    }

    public function ofType(...$type): bool
    {
        foreach ($type as $_type) {
            if ($this->filterType->get() == $_type) {
                return true;
            }
        }

        return false;
    }

    abstract public function apply($value = null): Closure;

    public function __get($key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        }

        if (!isset($this->values[$key])) {
            return null;
        }

        return $this->values[$key];
    }

    public function render(array $requestInput = []): string
    {
        $path = $this->viewpath ?? 'chief::back._filters.' . $this->type;
        $this->default($requestInput[$this->name] ?? null);

        return view($path, ['filter' => $this])->render();
    }

    public function __call($name, $arguments)
    {
        // Without arguments we assume you want to retrieve a value property, except for query() which is used on a custom Filter.
        if (empty($arguments) && !in_array($name, ['apply'])) {
            return $this->__get($name);
        }

        if (!in_array($name, ['name', 'label', 'description', 'query', 'viewpath', 'default'])) {
            throw new \InvalidArgumentException('Cannot set value by [' . $name . '].');
        }

        $this->values[$name] = $arguments[0];

        return $this;
    }
}
