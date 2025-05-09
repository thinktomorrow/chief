<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Resource;

use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class ResourceFormsTest extends FormsTestCase
{
    public function test_it_provides_chief_with_resource_info()
    {
        $stub = new ResourceStub;

        $this->assertEquals('resource stub', $stub->getLabel());
        $this->assertEquals('resource_stub', $stub::resourceKey());
        $this->assertEquals(ResourceStub::class, $stub::modelClassName());
    }
}
