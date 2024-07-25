<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasView
{
    public function getView(): string
    {
        return $this->view;
    }

    public function setView(string $view): static
    {
        $this->view = $view;

        return $this;
    }
}
