<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasPreviewLabel
{
    protected ?string $previewLabel = null;

    public function previewLabel(string $previewLabel): static
    {
        $this->previewLabel = $previewLabel;

        return $this;
    }

    public function getPreviewLabel(): ?string
    {
        if (! $this->previewLabel && isset($this->label) && $this->label) {
            return $this->label;
        }

        return $this->previewLabel;
    }
}
