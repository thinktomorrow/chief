<?php

namespace Thinktomorrow\Chief\Assets\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Assets\Livewire\HasSyncedFormInputs;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;

class FileSelect extends Component implements Htmlable
{
    private HasSyncedFormInputs $component;
    private bool $allowToUploadFiles;
    private bool $allowToChooseFiles;
    private bool $allowToChooseExternalFiles;

    public function __construct(HasSyncedFormInputs $component, bool $allowToUploadFiles = true, bool $allowToChooseFiles = true, bool $allowToChooseExternalFiles = false)
    {
        $this->component = $component;
        $this->allowToUploadFiles = $allowToUploadFiles;
        $this->allowToChooseFiles = $allowToChooseFiles;
        $this->allowToChooseExternalFiles = $allowToChooseExternalFiles;
    }

    public function toHtml()
    {
        return $this->render()->render();
    }

    public function render(): View
    {
        return view('chief-assets::components.select', array_merge($this->data()));
    }

    public function getFieldId(): string
    {
        return $this->component->getFieldId();
    }

    public function getFieldName(): string
    {
        return $this->component->getFieldName();
    }

    public function getFilesForUpload(): Collection
    {
        return collect($this->component->getPreviewFiles())->reject(fn (PreviewFile $file) => ($file->isAttachedToModel || $file->isUploading || $file->isQueuedForDeletion || $file->mediaId));
    }

    public function getFilesForAttach(): Collection
    {
        return collect($this->component->getPreviewFiles())->filter(fn (PreviewFile $file) => ($file->mediaId && ! $file->isQueuedForDeletion));
    }

    public function getFilesForDeletion(): Collection
    {
        return collect($this->component->getPreviewFiles())->filter(fn (PreviewFile $file) => ($file->isAttachedToModel && $file->isQueuedForDeletion));
    }

    public function getFiles(): Collection
    {
        return collect($this->component->getPreviewFiles());
    }

    public function getFilesCount(): int
    {
        return count(collect($this->component->getPreviewFiles()));
    }

    public function allowMultiple(): bool
    {
        return $this->component->areMultipleFilesAllowed();
    }

    public function acceptedMimeTypes(): ?string
    {
        $mimeTypes = $this->component->getAcceptedMimeTypes();

        return implode(', ', $mimeTypes);
    }

    public function allowToUploadFiles(): bool
    {
        return $this->allowToUploadFiles;
    }

    public function allowToChooseFiles(): bool
    {
        return $this->allowToChooseFiles;
    }

    public function allowToChooseExternalFiles(): bool
    {
        return $this->allowToChooseExternalFiles;
    }
}
