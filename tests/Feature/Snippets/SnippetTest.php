<?php

namespace Thinktomorrow\Chief\Tests\Feature\Snippets;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Snippets\Snippet;
use Thinktomorrow\Chief\Tests\TestCase;

class SnippetTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief-settings.snippets', [
            'snippet-id'   => [
                'label' => 'snippet stub',
                'view' => __DIR__.'/snippet-stub.html'
            ],
        ]);
    }
    
    /** @test */
    function it_can_render_a_snippet()
    {
        $this->assertEquals('<p>This is a snippet</p>', Snippet::find('snippet-id')->render());
    }
    
    /** @test */
    function it_can_list_all_snippets()
    {
        $this->assertCount(1, Snippet::all());
        $this->assertInstanceOf(Snippet::class, Snippet::all()->first());
    }
}
