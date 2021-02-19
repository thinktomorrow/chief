<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Snippets;

use Thinktomorrow\Chief\Shared\Snippets\SnippetCollection;
use Thinktomorrow\Chief\Shared\Snippets\SnippetParser;
use Thinktomorrow\Chief\Tests\TestCase;

class SnippetParserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('chief.loadSnippetsFrom', [
            realpath(__DIR__ . '/snippet-stub.html'),
        ]);

        SnippetCollection::refresh();
    }

    /** @test */
    public function it_does_not_parse_value_without_valid_snippet_key()
    {
        $this->assertEquals('<p>This is untouched</p>', SnippetParser::parse('<p>This is untouched</p>'));
        $this->assertEquals('<p>This is [also] untouched</p>', SnippetParser::parse('<p>This is [also] untouched</p>'));
        $this->assertEquals('<p>This is [[also] untouched</p>', SnippetParser::parse('<p>This is [[also] untouched</p>'));

        // Valid snippet expression but does not exist as key
        $this->assertEquals('<p>This is [[also]] untouched</p>', SnippetParser::parse('<p>This is [[also]] untouched</p>'));
    }

    /** @test */
    public function it_can_parse_a_value_that_contains_a_snippet_key()
    {
        $this->assertEquals('<p>This is <p>This is a snippet</p> untouched</p>', SnippetParser::parse('<p>This is [[snippet-stub]] untouched</p>'));
    }

    /** @test */
    public function it_can_parse_multiple_snippet_keys_in_one_string()
    {
        $this->assertEquals('<p>This is <p>This is a snippet</p> <p>This is a snippet</p> untouched</p>', SnippetParser::parse('<p>This is [[snippet-stub]] [[snippet-stub]] untouched</p>'));
    }
}
