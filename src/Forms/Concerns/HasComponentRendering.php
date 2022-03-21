<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

use Illuminate\Contracts\View\View;

trait HasComponentRendering
{
    public function render(): View
    {
        return view($this->getView(), array_merge($this->data(), [
            'component' => $this,
        ]));
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }
}
