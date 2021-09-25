<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Thinktomorrow\Chief\ManagedModels\Fields\Field;

class SelectField extends AbstractField implements Field
{
    use AllowsMultiple;
    use AllowsOptions;

    protected bool $grouped = false;

    // Use regular select instead of vue multi-select
    private bool $prefersNativeSelect = false;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::SELECT), $key);
    }

    /**
     * @return static
     */
    public function grouped(bool $grouped = true): self
    {
        $this->grouped = $grouped;

        return $this;
    }

    public function isGrouped(): bool
    {
        return $this->grouped;
    }

    public function preferNativeSelect(): self
    {
        $this->prefersNativeSelect = true;

        return $this;
    }

    public function prefersNativeSelect(): bool
    {
        return $this->prefersNativeSelect;
    }
}
