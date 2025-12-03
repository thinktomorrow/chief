<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

trait HasView
{
    protected string $view;

    protected array $viewData = [];

    public function getView(): string
    {
        return $this->view;
    }

    public function setView(string $view, array $viewData = []): static
    {
        $this->view = $view;
        $this->viewData = $viewData;

        return $this;
    }

    public function getViewData(): array
    {
        return $this->viewData;
    }
}
