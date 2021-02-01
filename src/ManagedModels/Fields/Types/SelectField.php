<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

class SelectField extends AbstractField implements Field
{
    use AllowsMultiple;
    use AllowsOptions;

    protected $grouped = false;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::SELECT), $key);
    }

    public function grouped(bool $grouped = true)
    {
        $this->grouped = $grouped;

        return $this;
    }

    public function isGrouped(): bool
    {
        return $this->grouped;
    }
}
