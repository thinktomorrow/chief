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
        return collect($this->components)->filter(fn ($component) => ! $component instanceof Form);
    }
}
