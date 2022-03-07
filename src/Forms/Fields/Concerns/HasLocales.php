<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Common\LocalizedFormKey;

trait HasLocales
{
    protected array $locales = [];

    protected string $localizedFormKeyTemplate = LocalizedFormKey::DEFAULT_TEMPLATE;

    public function locales(?array $locales = null): static
    {
        $this->locales = (null === $locales)
            ? config('chief.locales', [])
            : $locales;

        return $this;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function hasLocales(): bool
    {
        return count($this->locales) > 0;
    }

    public function getLocalizedFormKey(): LocalizedFormKey
    {
        return LocalizedFormKey::make()
            ->bracketed()
            ->template(str_contains($this->name, ':locale') ? ':name' : $this->localizedFormKeyTemplate);
    }
}
