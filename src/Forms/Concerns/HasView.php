<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasView
{
    protected string $view;
    protected string $windowView;

    protected bool $editInSidebar = false;

    public function getView(): string
    {
        return ($this->editInSidebar && isset($this->windowView))
            ? $this->windowView
            : $this->view;
    }

    public function setView(string $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function windowView(string $windowView): static
    {
        $this->windowView = $windowView;

        return $this;
    }

    public function editInSidebar(?string $windowView = null): static
    {
        $this->editInSidebar = true;

        if ($windowView) {
            $this->windowView($windowView);
        }

        return $this;
    }

    public function editInline(): static
    {
        $this->editInSidebar = false;

        return $this;
    }
}
