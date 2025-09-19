<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs\Concerns;

trait HasButton
{
    protected ?string $button = null;

    protected string $buttonVariant = 'blue';

    public function button(string $button): static
    {
        $this->button = $button;

        return $this;
    }

    public function buttonVariant(string $variant): static
    {
        $this->buttonVariant = $variant;

        return $this;
    }

    public function getButton(): ?string
    {
        return $this->button;
    }

    public function getButtonVariant(): string
    {
        return $this->buttonVariant;
    }
}
