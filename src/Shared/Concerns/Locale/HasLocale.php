<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Locale;

trait HasLocale
{
    private ?string $locale = null;

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    protected function getLocale(): string
    {
        if (! $this->locale) {
            return app()->getLocale();
        }

        return $this->locale;
    }
}
