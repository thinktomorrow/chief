<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Layouts\Form;

trait HasFields
{
    public function getFields(): Fields
    {
        return Fields::makeWithoutFlatteningNestedFields($this->components);
    }

    public function getAllFields(): Fields
    {
        return Fields::make($this->components);
    }

    public function getComponentsWithoutForms(): Collection
    {
        $components = collect();

        foreach ($this->components as $component) {
            if ($component instanceof Form) {
                $components->push(...$component->getComponents());
            } else {
                $components->push($component);
            }
        }

        return $components;
    }
}
