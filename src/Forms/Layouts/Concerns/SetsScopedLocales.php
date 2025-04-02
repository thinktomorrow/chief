<?php

namespace Thinktomorrow\Chief\Forms\Layouts\Concerns;

trait SetsScopedLocales
{
    public function setScopedLocales(array $locales): self
    {
        foreach ($this->getAllFields() as $field) {
            $field->setScopedLocales($locales);
        }

        return $this;
    }

    public function setDormantLocales(array $locales): self
    {
        foreach ($this->getAllFields() as $field) {
            $field->setDormantLocales($locales);
        }

        return $this;
    }
}
