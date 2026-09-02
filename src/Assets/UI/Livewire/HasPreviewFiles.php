<?php

namespace Thinktomorrow\Chief\Assets\UI\Livewire;

interface HasPreviewFiles
{
    /** @return PreviewFile[] */
    public function getPreviewFiles(): array;

    public function areMultipleFilesAllowed(): bool;
}
