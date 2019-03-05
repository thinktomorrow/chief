<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Filters;

use Closure;
use Thinktomorrow\Chief\Filters\Types\FilterType;

class Filter
{
    /** @var FilterType */
    private $filterType;

    protected $values = [];

    public function __construct(FilterType $filterType, string $key)
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
        // Without arguments we assume you want to retrieve a value property
        if (empty($arguments)) {
            return $this->__get($name);
        }

        if (!in_array($name, ['name', 'label', 'description', 'query', 'viewpath', 'default'])) {
            throw new \InvalidArgumentException('Cannot set value by ['. $name .'].');
        }

        $this->values[$name] = $arguments[0];

        return $this;
    }
}
