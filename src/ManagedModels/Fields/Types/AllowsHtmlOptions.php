<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsHtmlOptions
{
    protected array $htmlOptions = [];

    public function htmlOptions($htmlOptions): self
    {
        $this->htmlOptions = (array) $htmlOptions;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->htmlOptions;
    }
}
