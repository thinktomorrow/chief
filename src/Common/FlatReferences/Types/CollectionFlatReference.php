<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\FlatReferences\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\FlatReferences\ActsAsFlatReference;

/**
 * Composite key consisting of the type of class combined with the
 * model id. Both are joined with an @ symbol. This is used as
 * identifier of the relation mostly as form values.
 *
 * @return CollectionFlatReference
 */
class CollectionFlatReference implements ActsAsFlatReference
{
    use CommonFlatReferenceBehaviour;

    /**
     * Recreate the model instance that is referred to by this collection id
     * @return Model
     */
    public function instance(): Model
    {
        return (new $this->className)->ignoreCollection()->findOrFail($this->id);
    }
}