<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs\Concerns;

trait HasSubTitle
{
    protected ?string $subTitle = null;

    public function subTitle(string $subTitle): static
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }
}
