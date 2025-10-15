<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasHideIfEmpty
{
    protected bool $hideIfEmpty = false;

    public function hideIfEmpty(bool $hideIfEmpty = true): static
    {
        $this->hideIfEmpty = $hideIfEmpty;

        return $this;
    }

    public function shouldHideInPreview(?string $locale = null): bool
    {
        if (! $this->hideIfEmpty) {
            return false;
        }

        return empty($this->getValueOrFallback($locale));
    }
}
