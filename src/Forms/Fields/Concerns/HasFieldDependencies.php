<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasFieldDependencies
{
    /**
     * Dependencies that should be resolved when the given field state changes.
     *
     * @var array<string,Closure>
     */
    protected array $fieldDependencies = [];

    public function dependsOn(string|array $fieldNames, Closure $callback): static
    {
        foreach ((array) $fieldNames as $fieldName) {
            $this->fieldDependencies[$fieldName] = $callback;
        }

        return $this;
    }

    public function getFieldDependencies(): array
    {
        return $this->fieldDependencies;
    }
}
