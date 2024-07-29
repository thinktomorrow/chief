<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Modals\Concerns;

trait HasContent
{
    protected ?string $content = null;

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
