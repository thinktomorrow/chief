<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Illuminate\Support\Collection;

interface HasSyncedFormInputs
{
    /** @return PreviewFile[] */
    public function getPreviewFiles(): array;

    public function areMultipleFilesAllowed(): bool;

    public function getFieldId(): string;

    public function getFieldName(): string;

    public function getAcceptedMimeTypes(): array;
}
