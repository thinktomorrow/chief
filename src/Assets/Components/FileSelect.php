<?php

namespace Thinktomorrow\Chief\Assets\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Assets\Livewire\FilesComponent;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;

class FileSelect extends Component implements Htmlable
{
    private FilesComponent $fileUploadComponent;

    public function __construct(FilesComponent $fileUploadComponent)
    {
        $this->fileUploadComponent = $fileUploadComponent;
    }

    public function toHtml()
    {
        return $this->render()->render();
    }

    public function getFieldId(): string
    {
        return $this->fileUploadComponent->id.'-'.$this->fileUploadComponent->fieldName;
    }

    public function getFieldName(): string
    {
        return $this->fileUploadComponent->fieldName;
    }

    public function getFilesForUpload(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles)->reject(fn (PreviewFile $file) => ($file->isAttachedToModel || $file->isQueuedForDeletion || $file->mediaId));
    }

    public function getFilesForAttach(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles)->filter(fn (PreviewFile $file) => ($file->mediaId && ! $file->isAttachedToModel && ! $file->isQueuedForDeletion));
    }

    public function getFilesForDeletion(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles)->filter(fn (PreviewFile $file) => ($file->isAttachedToModel && $file->isQueuedForDeletion));
    }

    public function getFiles(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles);
    }

    public function getFilesCount(): int
    {
        return count(collect($this->fileUploadComponent->previewFiles));
    }

    public function allowMultiple(): bool
    {
        return $this->fileUploadComponent->allowMultiple;
    }

    public function acceptedMimeTypes(): ?string
    {
        $mimeTypes = $this->fileUploadComponent->acceptedMimeTypes;

        return implode(', ', $mimeTypes);
    }

    public function render(): View
    {
        return view('chief-assets::select', array_merge($this->data()));
    }
}
