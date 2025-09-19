<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;

class Window extends LayoutComponent implements Layout
{
    use HasDescription;
    use HasTitle;

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

    protected function wireableMethods(array $components): array
    {
        return array_merge(parent::wireableMethods($components), [
            ...(isset($this->description) ? ['description' => $this->description] : []),
            ...(isset($this->title) ? ['title' => $this->title] : []),
        ]);
    }
}
