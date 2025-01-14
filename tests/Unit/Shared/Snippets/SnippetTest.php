<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Snippets;

use Thinktomorrow\Chief\Shared\Snippets\Snippet;
use Thinktomorrow\Chief\Shared\Snippets\SnippetCollection;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class SnippetTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('chief.loadSnippetsFrom', [
            realpath(__DIR__ . '/snippet-stub.html'),
        ]);
    }

    public function test_it_can_fetch_a_snippet()
    {
        $snippet = SnippetCollection::find('snippet-stub');

        $this->assertInstanceOf(Snippet::class, $snippet);
        $this->assertEquals('snippet-stub', $snippet->key());
        $this->assertEquals('Snippet stub', $snippet->label());
        $this->assertEquals(realpath(__DIR__ . '/snippet-stub.html'), $snippet->path());
    }

    public function test_it_can_render_a_snippet()
    {
        $this->assertEquals('<p>This is a snippet</p>', SnippetCollection::find('snippet-stub')->render());
    }

    public function test_it_can_list_all_snippets()
    {
        $this->assertCount(1, SnippetCollection::load());
        $this->assertInstanceOf(Snippet::class, SnippetCollection::load()->first());
    }

    public function test_it_ignores_invalid_loading_path()
    {
        $this->app['config']->set('chief.loadSnippetsFrom', [
            false,
        ]);

        SnippetCollection::refresh();

        $this->assertCount(0, SnippetCollection::load());
    }
}
