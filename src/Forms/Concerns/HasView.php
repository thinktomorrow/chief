<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasView
{
    protected string $view;
    protected string $windowView;

    protected bool $displayInWindow = false;

    public function view(string $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function getView(): string
    {
        return ($this->displayInWindow && isset($this->windowView))
            ? $this->windowView
            : $this->view;
    }

    public function displayInWindow(?string $windowView = null): static
    {
        $this->displayInWindow = true;

        if($windowView) $this->windowView = $windowView;

        return $this;
    }

    public function displayInForm(): static
    {
        $this->displayInWindow = false;

        return $this;
    }
}
