<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

use Illuminate\Contracts\View\View;

trait HasComponentRendering
{
    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function render(): View
    {
        return view($this->getView(), array_merge($this->data(), [
            'component' => $this,
            ...$this->getViewData(),
        ]));
    }

    public function renderPreview(): View
    {
        return view($this->getPreviewView(), array_merge($this->data(), [
            'component' => $this,
            ...$this->getPreviewViewData(),
        ]));
    }
}
