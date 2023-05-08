<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\FilesComponent;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\PreviewFile;

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
        return $this->fileUploadComponent->fieldId;
    }

    public function getFieldName(): string
    {
        return $this->fileUploadComponent->fieldName;
    }

    public function getFilesForUpload(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles)->reject(fn(PreviewFile $file) => ($file->mediaId || $file->isQueuedForDeletion));
    }

    public function getFilesForDeletion(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles)->filter(fn(PreviewFile $file) => ($file->mediaId && $file->isQueuedForDeletion));
    }

    public function getFiles(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles);
    }

    public function allowMultiple(): bool
    {
        return $this->fileUploadComponent->allowMultiple;
    }

    public function render(): View
    {
        $view = 'chief-form::fields.file.select';

        return view($view, array_merge($this->data(), [
//            'id' => $this->fileUploadComponent->getFieldId(),
//            'name' => $this->fileUploadComponent->getFieldName(),
        ]));
    }
}

