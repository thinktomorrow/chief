<?php

namespace Thinktomorrow\Chief\Forms\Layouts\Concerns;

trait WithLocalizedFields
{
    public function setScopedLocale(string $locale): self
    {
        foreach ($this->getAllFields() as $field) {
            if ($field->showsLocales()) {
                $field->setLocales([$locale]);
            }
        }

        return $this;
    }

    public function setLocales(array $locales): self
    {
        foreach ($this->getAllFields() as $field) {
            if ($field->showsLocales()) {
                $field->setLocales($locales);
            }
        }

        return $this;
    }
}
