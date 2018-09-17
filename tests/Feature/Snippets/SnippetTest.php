<?php

namespace Thinktomorrow\Chief\Tests\Feature\Snippets;

use Thinktomorrow\Chief\Snippets\Snippet;
use Thinktomorrow\Chief\Snippets\SnippetCollection;
use Thinktomorrow\Chief\Tests\TestCase;

class SnippetTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.loadSnippetsFrom', [
            realpath(__DIR__.'/snippet-stub.html'),
        ]);
    }

    /** @test */
    public function it_can_fetch_a_snippet()
    {
        $snippet = SnippetCollection::find('snippet-stub');

        $this->assertInstanceOf(Snippet::class, $snippet);
        $this->assertEquals('snippet-stub', $snippet->key());
        $this->assertEquals('Snippet stub', $snippet->label());
        $this->assertEquals(realpath(__DIR__.'/snippet-stub.html'), $snippet->path());
    }
    
    /** @test */
    public function it_can_render_a_snippet()
    {
        $this->assertEquals('<p>This is a snippet</p>', SnippetCollection::find('snippet-stub')->render());
    }
    
    /** @test */
    public function it_can_list_all_snippets()
    {
        $this->assertCount(1, SnippetCollection::load());
        $this->assertInstanceOf(Snippet::class, SnippetCollection::load()->first());
    }

    /** @test */
    public function it_ignores_invalid_loading_path()
    {
        $this->app['config']->set('thinktomorrow.chief.loadSnippetsFrom', [
            false,
        ]);

        SnippetCollection::refresh();

        $this->assertCount(0, SnippetCollection::load());
    }
}
