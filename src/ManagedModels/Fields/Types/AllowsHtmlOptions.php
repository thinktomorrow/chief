<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsHtmlOptions
{
    protected array $htmlOptions = [];

    public function htmlOptions($htmlOptions): self
    {
        $this->htmlOptions = (array) $htmlOptions;

        return $this;
    }

    public function getHtmlOptions(string $key, ?string $locale = null): array
    {
        // Provide rtl direction
        if ($locale && in_array($locale, ['ar'])) {
            $this->htmlOptions = array_merge($this->htmlOptions, ['direction' => 'rtl']);
        }

        return $this->redactorMapping($this->htmlOptions);
    }

    private function redactorMapping(array $options): array
    {
        if (count($this->htmlOptions) < 1) {
            return [];
        }

        /*
         * We expect the options to be of following format.
         * [
         *      ... options
         *      buttons => [...],
         *      plugins => [...],
         * ]
         */
        return $options;
    }
}
