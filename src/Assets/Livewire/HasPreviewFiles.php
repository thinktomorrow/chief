<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

interface HasPreviewFiles
{
    /** @return PreviewFile[] */
    public function getPreviewFiles(): array;

    public function areMultipleFilesAllowed(): bool;
}
