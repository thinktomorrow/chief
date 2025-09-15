<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

class Window extends LayoutComponent implements Layout
{
    protected string $variant = 'card';

    protected string $view = 'chief-form::layouts.window';

    protected string $previewView = 'chief-form::layouts.window';

    public function useCardVariant(): static
    {
        return $this->setVariant('card');
    }

    public function useTransparentVariant(): static
    {
        return $this->setVariant('transparent');
    }

    public function useDefaultVariant(): static
    {
        return $this->setVariant('');
    }

    public function getVariant(): string
    {
        return $this->variant;
    }

    public function setVariant(string $variant): static
    {
        $this->variant = $variant;

        return $this;
    }
}
