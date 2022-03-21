<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;

trait HasId
{
    protected string $id;

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(?string $locale = null): string
    {
        if ($locale) {
            return $this->getLocalizedFormKey()->dotted()->get($this->id, $locale);
        }

        return FormKey::replaceBracketsByDots($this->id);
    }
}
