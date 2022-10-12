<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasView
{
    protected string $view;

    public function getView(): string
    {
        return $this->view;
    }

    public function view(string $view): static
    {
        $this->view = $view;

        return $this;
    }
}
