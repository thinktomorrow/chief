<?php

namespace Thinktomorrow\Chief\Assets\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Assets\Livewire\HasPreviewFiles;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;

class FilePreview extends Component implements Htmlable
{
    private HasPreviewFiles $component;

    public function __construct(HasPreviewFiles $component)
    {
        $this->component = $component;
    }

    /**
     * @return Collection<PreviewFile>
     */
    public function getFiles(): Collection
    {
        return collect($this->component->getPreviewFiles());
    }

    public function allowMultiple(): bool
    {
        return $this->component->areMultipleFilesAllowed();
    }

    public function toHtml()
    {
        return $this->render()->render();
    }

    public function render(): View
    {
        $view = 'chief-assets::components.preview';

        return view($view, array_merge($this->data(), [

        ]));
    }
}
