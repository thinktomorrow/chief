<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasHideInPreview
{
    protected bool $hideInPreview = false;

    protected bool $hideInPreviewIfEmpty = false;

    public function hideInPreviewIfEmpty(bool $hideIfEmpty = true): static
    {
        $this->hideInPreviewIfEmpty = $hideIfEmpty;

        return $this;
    }

    public function hideInPreview(bool $hideInPreview = true): static
    {
        $this->hideInPreview = $hideInPreview;

        return $this;
    }

    /**
     * @deprecated use shouldHideInPreview() instead
     */
    public function hideIfEmpty(bool $hideIfEmpty = true): static
    {
        return $this->hideInPreviewIfEmpty($hideIfEmpty);
    }

    public function shouldHideInPreview(?string $locale = null): bool
    {
        if ($this->hideInPreview) {
            return true;
        }

        if (! $this->hideInPreviewIfEmpty) {
            return false;
        }

        return empty($this->getValueOrFallback($locale));
    }
}
