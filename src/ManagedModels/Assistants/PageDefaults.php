<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\Publishable;
use Thinktomorrow\Chief\ManagedModels\States\UsesPageState;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidingUrl;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait PageDefaults
{
    use FragmentableDefaults;
    use Viewable;
    use ProvidingUrl;
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
}
