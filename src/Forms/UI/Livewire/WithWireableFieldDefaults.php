<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Thinktomorrow\Chief\Forms\Concerns\HasComponents;

trait WithWireableFieldDefaults
{
    protected function wireableMethods(array $components): array
    {
        return [];
    }

    public static function fromLivewire($value)
    {
        $component = static::make($value['key']);

        foreach ($value['methods'] as $method => $parameters) {

            if ($method == 'components') {
                $parameters = static::unpackComponentsFromLivewire($parameters);
            }

            $component->{$method}($parameters);
        }

        return $component;
    }

    public function toLivewire()
    {
        if (isset($this->options) && is_callable($this->options)) {
            $this->options = call_user_func($this->options);
        }

        // recursive loop for nested items ...
        $components = $this->packComponentsToLivewire();

        return [
            'class' => static::class,
            'key' => $this->key,
            'methods' => $this->wireableMethods($components),
        ];
    }

    private function packComponentsToLivewire(): array
    {
        if (! $this instanceof HasComponents) {
            return [];
        }

        $converted = [];

        foreach ($this->getComponents() as $component) {
            $converted[] = $component->toLivewire();
        }

        return $converted;
    }

    private static function unpackComponentsFromLivewire(array $packedComponents): array
    {
        return collect($packedComponents)->map(function ($component) {
            return $component['class']::fromLivewire($component);
        })->all();
    }
}
