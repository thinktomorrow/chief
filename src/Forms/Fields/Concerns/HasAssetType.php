<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasAssetType
{
    private ?string $assetType = null;

    /**
     * Save the file as a specific asset type class
     *
     * This value should refer to one of the types
     * as defined in the assetlibrary.types config.
     *
     * @return $this
     */
    public function assetType(string $assetType): static
    {
        $this->assetType = $assetType;

        return $this;
    }

    public function getAssetType(): ?string
    {
        return $this->assetType ?: null;
    }
}
