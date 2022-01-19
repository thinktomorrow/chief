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
        if ($locale) {
            return $this->getLocalizedFormKey()->get($this->name, $locale);
        }

        return $this->name;
    }
}
