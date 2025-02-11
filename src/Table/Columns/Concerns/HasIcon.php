<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Illuminate\Support\Facades\Blade;

trait HasIcon
{
    protected ?string $prependIcon = null;

    protected ?string $appendIcon = null;

    public function getPrependIcon(): ?string
    {
        return $this->prependIcon;
    }

    public function prependIcon(string $icon): static
    {
        $this->prependIcon = $this->renderIcon($icon);

        return $this;
    }

    public function getAppendIcon(): ?string
    {
        return $this->appendIcon;
    }

    public function appendIcon(string $icon): static
    {
        $this->appendIcon = $this->renderIcon($icon);

        return $this;
    }

    public function icon(string $icon): static
    {
        return $this->prependIcon($icon)->label('');
    }

    public function iconEdit(): static
    {
        return $this->icon('<x-chief::icon.quill-write />');
    }

    public function iconView(): static
    {
        return $this->icon('<x-chief::icon.view />');
    }

    public function iconAdd(): static
    {
        return $this->icon('<x-chief::icon.plus-sign />');
    }

    public function iconDelete(): static
    {
        return $this->icon('<x-chief::icon.delete />');
    }

    private function renderIcon(string $icon): string
    {
        if (str_starts_with($icon, '<x-chief::icon')) {
            return Blade::render($icon);
        }

        return $icon;
    }
}
