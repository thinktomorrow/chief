<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\Publishable;
use Thinktomorrow\Chief\ManagedModels\States\UsesPageState;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

trait PageDefaults
{
    use ReferableModelDefault;
    use ManagedModelDefaults;
    use Viewable;
    use VisitableDefaults;
    use OwningFragments;

    use UsesPageState;
    use Publishable;
    use Archivable;

    use AssetTrait;
    use HasDynamicAttributes;

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function dynamicLocales(): array
    {
        return config('chief.locales');
    }

    /**
     * As a default, we'll guess the dynamic keys based on the provided fields. This should give you a
     * nice and clean setup. Should you need to customize the dynamic keys, you'll be able to define
     * a dynamicKeys property on the model. This will circumvent the logic below.
     * @return array
     */
    protected function dynamicKeys(): array
    {
        if (property_exists($this, 'dynamicKeys')) {
            return $this->dynamicKeys;
        }

        return collect(Fields::make($this->fields())->all())->map(function ($field) {
            return $field->getColumn();
        })->values()->toArray();
    }
}
