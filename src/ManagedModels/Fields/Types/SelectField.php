<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

class SelectField extends AbstractField implements Field
{
    use AllowsMultiple;
    use AllowsOptions;

    protected bool $grouped = false;

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
}
