<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\FilesComponent;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\PreviewFile;

class FilePreview extends Component implements Htmlable
{
    private FilesComponent $fileUploadComponent;

    public function __construct(FilesComponent $fileUploadComponent)
    {
        $this->fileUploadComponent = $fileUploadComponent;
    }

    /**
     * @return Collection<PreviewFile>
     */
    public function getFiles(): Collection
    {
        return collect($this->fileUploadComponent->previewFiles);
    }

    public function allowMultiple(): bool
    {
        return $this->fileUploadComponent->allowMultiple;
    }

    public function toHtml()
    {
        return $this->render()->render();
    }

    public function render(): View
    {
        $view = 'chief-form::fields.file.preview';

        return view($view, array_merge($this->data(), [

        ]));
    }
}
