<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

trait HasLocale
{
    protected ?string $locale = null;

    public function locale(?string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function hasLocales(): bool
    {
        return $this->locale !== null;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }
}
