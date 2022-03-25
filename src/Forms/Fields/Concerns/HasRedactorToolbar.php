<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasRedactorToolbar
{
    /*
     * We expect the options to be of the format the redactor expects them to be. e.g.:
     *      [
     *          ... options
     *          buttons => [...],
     *          plugins => [...],
     *      ]
     */
    protected array $redactorOptions = [];

    public function redactorOptions(array $options): self
    {
        $this->redactorOptions = $this->sanitizeOptions($options);

        return $this;
    }

    public function hasRedactorOptions(): bool
    {
        return count($this->redactorOptions) > 0;
    }

    public function getRedactorOptions(?string $locale = null): array
    {
        // Provide rtl direction
        if ($locale && in_array($locale, ['ar'])) {
            $this->redactorOptions = array_merge($this->redactorOptions, ['direction' => 'rtl']);
        }

        return array_merge($this->defaultRedactorOptions($locale), $this->redactorOptions);
    }

    private function defaultRedactorOptions(): array
    {
        return [
            
        ];
    }

    private function sanitizeOptions(array $options): array
    {
        foreach(['buttons', 'plugins'] as $type) {
            if(isset($options[$type])) {
                $options[$type] = (array) $options[$type];
            }
        }

        return $options;
    }
}
