<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Locale;

trait HasLocale
{
    private ?string $locale = null;

    protected function getLocale(): string
    {
        if (! $this->locale) {
            return app()->getLocale();
        }

        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }
}
