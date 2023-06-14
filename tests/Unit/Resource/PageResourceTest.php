<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Resource;

use Thinktomorrow\Chief\Forms\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class PageResourceTest extends TestCase
{
    /** @test */
    public function it_provides_chief_with_resource_info()
    {
        $stub = new PageResourceStub();
        $model = new SnippetStub();

        $this->assertEquals('snippet stub', $stub->getLabel());
        $this->assertEquals('snippet_stub', $stub::resourceKey());
        $this->assertEquals(SnippetStub::class, $stub::modelClassName());

        $this->assertEquals('snippet stub', $stub->getPageTitle($model));
        $this->assertEquals('Snippet stubs', $stub->getIndexTitle());
    }
}
