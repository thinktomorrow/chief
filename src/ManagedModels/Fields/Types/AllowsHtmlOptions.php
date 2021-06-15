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

    public function getHtmlOptions(string $key): array
    {
        return $this->redactorMapping($this->htmlOptions);
    }

    private function redactorMapping(array $options): array
    {
        if(count($this->htmlOptions) < 1) return [];

        if(isset($options['buttons'], $options['plugins'])) return $options;

        // Map the settings to the appropriate format, it's ok when both plugins and
        // buttons have the same values since unknown options will be ignored
        return ['plugins' => $options, 'buttons' => $options];
    }
}
