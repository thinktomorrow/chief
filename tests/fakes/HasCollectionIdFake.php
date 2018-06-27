<?php

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Common\Collections\CollectionId;
use Thinktomorrow\Chief\Common\Collections\HasCollectionId;

class HasCollectionIdFake implements HasCollectionId
{
    private $id;
    private $label;
    private $group;

    public function __construct($id, $label, $group)
    {
        $this->id = $id;
        $this->label = $label;
        $this->group = $group;
    }

    public function getCollectionId(): CollectionId
    {
        return new CollectionId(get_class($this), $this->id);
    }

    public function getCollectionLabel(): string
    {
        return $this->label;
    }

    public function getCollectionGroup(): string
    {
        return $this->group;
    }
}