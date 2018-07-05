<?php

namespace Thinktomorrow\Chief\Common\FlatReferences;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\FlatReferences\Types\CollectionFlatReference;

/**
 * Composite key consisting of the type of class combined with the
 * model id. Both are joined with an @ symbol. This is used as
 * identifier of the relation mostly as form values.
 *
 * @return CollectionFlatReference
 */
interface ActsAsFlatReference
{
    public function get(): string;

    public function equals($other): bool;

    public function __toString();

    /**
     * Recreate the model instance that is referred to by this model reference
     * @return Model
     */
    public function instance(): Model;
}
