<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Common\LocalizedFormKey;

trait HasLocales
{
    protected array $locales = [];

    protected ?string $localizedFormKeyTemplate = null;

    public function locales(?array $locales = null): static
    {
        $this->locales = ($locales === null)
            ? config('chief.locales', [])
            : $locales;

        return $this;
    }

    public function hasLocales(): bool
    {
        return count($this->locales) > 0;
    }

    public function getLocalizedKeys(): array
    {
        return $this->getLocalizedFormKey()
            ->dotted()
            ->matrix($this->getKey(), $this->getLocales());
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

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function getLocalizedNames(): array
    {
        return $this->getLocalizedFormKey()
            ->bracketed()
            ->matrix($this->getName(), $this->getLocales());
    }

    public function getLocalizedDottedNames(): array
    {
        return $this->getLocalizedFormKey()
            ->dotted()
            ->matrix($this->getName(), $this->getLocales());
    }
}
