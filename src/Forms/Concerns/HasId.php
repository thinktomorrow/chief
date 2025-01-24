<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldNameHelpers;

trait HasId
{
    protected string $id;

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return FieldNameHelpers::replaceBracketsByDots($this->id);
    }
}
