<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

class FragmentTest extends ChiefTestCase
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
}
