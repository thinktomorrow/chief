<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Resource;

use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class ResourceTest extends TestCase
{
    /** @test */
    public function it_provides_chief_with_resource_info()
    {
        $stub = new ResourceStub();

        $this->assertEquals('resource stub', $stub->getLabel());
        $this->assertEquals('resource_stub', $stub::resourceKey());
        $this->assertEquals(ResourceStub::class, $stub::modelClassName());

        // It can find field as well
        $this->assertEquals('first', $stub->field(new SnippetStub(), 'first')->getKey());
    }
}
