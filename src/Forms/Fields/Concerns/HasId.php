<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

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
        if(!method_exists($this, 'getLocalizedFormKey')) {
            throw new \Exception('Missing method getLocalizedFormKey.');
        }

        if ($locale) {
            return $this->getLocalizedFormKey()->dotted()->get($this->id, $locale);
        }

        return FormKey::replaceBracketsByDots($this->id);
    }
}
