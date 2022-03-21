<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasTitle
{
    protected ?string $title = null;

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
