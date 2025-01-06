<?php

namespace Thinktomorrow\Chief\Table\Tests\Fixtures;

use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Resource\TreeResourceDefault;

class TreeResourceFixture implements TreeResource
{
    use TreeResourceDefault;

    public static function modelClassName(): string
    {
        return TreeModelFixture::class;
    }
}
