<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasName
{
    protected string $name;

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(?string $locale = null): string
    {
        return $this->getFieldName()->get($this->name, $locale);
    }

    public function getRawName(): string
    {
        return $this->name;
    }
}
