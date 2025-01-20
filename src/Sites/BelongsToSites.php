<?php

namespace Thinktomorrow\Chief\Sites;

use Thinktomorrow\Chief\Forms\Fields\Locales\FieldLocales;

interface BelongsToSites
{
    /** All sites where this model is active in. */
    public function getSites(): ChiefSites;

    public function getFieldLocales(): FieldLocales;
}
