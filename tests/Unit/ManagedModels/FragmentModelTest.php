<?php

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class FragmentModelTest extends ChiefTestCase
{
    /** @test */
    public function it_cascade_calls_to_fragmentmodel()
    {
        $snippet = $this->setupAndCreateSnippet($this->setupAndCreateArticle(), 0, true, [
            'title' => 'foobar',
        ]);

        $this->assertEquals($snippet->fragmentModel()->title, $snippet->title);
        $this->assertEquals('foobar', $snippet->title);
    }

    /** @test */
    public function uknown_call_to_fragmentmodel_results_in_null()
    {
        $snippet = $this->setupAndCreateSnippet($this->setupAndCreateArticle());

        $this->assertNull($snippet->unknown_value);
    }
}
