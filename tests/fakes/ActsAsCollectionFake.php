<?php

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Common\Collections\ActingAsCollection;
use Thinktomorrow\Chief\Common\Collections\CollectionDetails;
use Thinktomorrow\Chief\Common\FlatReferences\ActsAsFlatReference;
use Thinktomorrow\Chief\Common\FlatReferences\Types\CollectionFlatReference;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;

class ActsAsCollectionFake implements ActsAsCollection
{
    use ActingAsCollection;

    private $id;
    private $label;
    private $group;

    public function __construct($id, $label, $group)
    {
        $this->id = $id;
        $this->label = $label;
        $this->group = $group;
    }

    public function flatReference(): ActsAsFlatReference
    {
        return new CollectionFlatReference(get_class($this), $this->id);
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