<?php

namespace Thinktomorrow\Chief\FlatReferences;

use Illuminate\Database\Eloquent\Model;

trait HasFlatReferences
{
    protected function flatReferenceToModel($value): Model
    {
        return FlatReferenceFactory::fromString($value)->instance();
    }
}
