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
        return $this->previewLabel ?: $this->getLabel();
    }
}
