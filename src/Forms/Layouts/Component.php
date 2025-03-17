<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasId;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
use Thinktomorrow\Chief\Forms\Concerns\WithComponents;
use Thinktomorrow\Chief\Forms\Livewire\PacksComponentsForLivewire;

abstract class Component extends \Illuminate\View\Component implements HasComponents, Htmlable, Wireable
{
    use HasComponentRendering;
    use HasCustomAttributes;
    use HasDescription;
    use HasId;
    use HasTitle;
    use HasView;
    use PacksComponentsForLivewire;
    use WithComponents;

    public function __construct(?string $id = null)
    {
        if (! $id) {
            $id = static::generateRandomId();
        }

        $this->id($id);
    }

    public static function make(?string $id = null)
    {
        return new static($id);
    }

    private static function generateRandomId(): string
    {
        return Str::random(10);
    }

    public static function fromLivewire($value)
    {
        $component = static::make();

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
        // recursive loop for nested items ...
        $components = $this->packComponentsToLivewire();

        return [
            'class' => static::class,
            'methods' => [
                ...(isset($this->id) ? ['id' => $this->id] : []),
                ...(isset($this->components) ? ['components' => $components] : []),
                ...(isset($this->customAttributes) ? ['customAttributes' => $this->customAttributes] : []),
                ...(isset($this->title) ? ['title' => $this->title] : []),
                ...(isset($this->description) ? ['description' => $this->description] : []),
                ...(isset($this->view) ? ['setView' => $this->view] : []),
                ...(isset($this->windowView) ? ['windowView' => $this->windowView] : []),
                ...(isset($this->columns) ? ['columns' => $this->columns] : []),
                ...(isset($this->collapsible) ? ['collapsible' => $this->collapsible] : []),
                ...(isset($this->collapsed) ? ['collapsed' => $this->collapsed] : []),
                ...(isset($this->layoutType) ? ['layoutType' => $this->layoutType->value] : []),

            ],
        ];
    }
}
