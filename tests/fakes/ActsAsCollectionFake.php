<?php

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Common\Collections\ActingAsCollection;
use Thinktomorrow\Chief\Common\Collections\CollectionDetails;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReference;

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