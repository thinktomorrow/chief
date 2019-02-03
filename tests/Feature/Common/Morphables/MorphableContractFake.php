<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common\Morphables;

use Thinktomorrow\Chief\Concerns\Morphable\Morphable;
use Thinktomorrow\Chief\Concerns\Morphable\CollectionDetails;
use Thinktomorrow\Chief\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\FlatReferences\FlatReference;

class MorphableContractFake implements MorphableContract
{
    use Morphable;

    private $id;
    private $label;
    private $group;

    public function __construct($id, $label, $group)
    {
        $this->id = $id;
        $this->label = $label;
        $this->group = $group;
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(get_class($this), $this->id);
    }

    public function collectionDetails($key = null): CollectionDetails
    {
        return new CollectionDetails(
            $this->id,
            get_class($this),
            $this->label,
            $this->label,
            $this->label
        );
    }

    public function flatReferenceLabel(): string
    {
        return $this->label;
    }

    public function flatReferenceGroup(): string
    {
        return $this->group;
    }
}
