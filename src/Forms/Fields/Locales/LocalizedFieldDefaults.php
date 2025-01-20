<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Locales;

use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\ChiefSites;

trait LocalizedFieldDefaults
{
    protected ?FieldLocales $fieldLocales = null;

    protected ?string $localizedFormKeyTemplate = null;

    public function locales(null|array|FieldLocales $locales = null): static
    {
        if((null === $locales)) {
            $this->fieldLocales = ChiefSites::fieldLocales();
        } elseif($locales instanceof FieldLocales) {
            $this->fieldLocales = $locales;
        } else {
            $this->fieldLocales = FieldLocales::fromArray($locales);
        }

        $this->whenModelIsSet(function ($model, $field) use ($locales) {
            if ($model instanceof BelongsToSites && (null === $locales)) {
                $field->fieldLocales = $model->getFieldLocales();
            }
        });

        return $this;
    }

    public function getFieldLocales(): ?FieldLocales
    {
        return $this->fieldLocales;
    }

    public function getLocales(): array
    {
        return $this->fieldLocales ? $this->fieldLocales->getLocales() : [];
    }

    public function hasLocales(): bool
    {
        return count($this->getLocales()) > 0;
    }

    public function getLocalizedKeys(): array
    {
        return $this->getLocalizedFormKey()
            ->dotted()
            ->matrix($this->getKey(), $this->getLocales());
    }

    public function getBracketedLocalizedNames(): array
    {
        return $this->getLocalizedFormKey()
            ->bracketed()
            ->matrix($this->getName(), $this->getLocales());
    }

    public function getDottedLocalizedNames(): array
    {
        return $this->getLocalizedFormKey()
            ->dotted()
            ->matrix($this->getName(), $this->getLocales());
    }

    public function getLocalizedFormKey(): LocalizedFormKey
    {
        return LocalizedFormKey::make()
            ->bracketed()
            ->template(str_contains($this->name, ':locale') ? ':name' : $this->getLocalizedFormKeyTemplate());
    }

    public function getLocalizedFormKeyTemplate(): string
    {
        if (! $this->localizedFormKeyTemplate) {
            return LocalizedFormKey::getDefaultTemplate();
        }

        return $this->localizedFormKeyTemplate;
    }

    public function setLocalizedFormKeyTemplate(string $localizedFormKeyTemplate): static
    {
        $this->localizedFormKeyTemplate = $localizedFormKeyTemplate;

        return $this;
    }
}
