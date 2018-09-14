<?php

namespace Thinktomorrow\Chief\Tests\Feature\Snippets;

use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Snippets\SnippetCollection;
use Thinktomorrow\Chief\Snippets\SnippetParser;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\Feature\Modules\ModuleFormParams;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\TestCase;

class SnippetParserTest extends TestCase
{
    use PageFormParams,
        ModuleFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.loadSnippetsFrom', [
            realpath(__DIR__.'/snippet-stub.html'),
        ]);

        SnippetCollection::refresh();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'articles' => ArticlePageFake::class,
            'text' => TextModule::class,
            'newsletter' => NewsletterModuleFake::class,
        ]);

        $this->page = ArticlePageFake::create(['collection' => 'articles']);
    }

    /** @test */
    function it_does_not_parse_value_without_valid_snippet_key()
    {
        $this->assertEquals('<p>This is untouched</p>', SnippetParser::parse('<p>This is untouched</p>'));
        $this->assertEquals('<p>This is [also] untouched</p>', SnippetParser::parse('<p>This is [also] untouched</p>'));
        $this->assertEquals('<p>This is [[also] untouched</p>', SnippetParser::parse('<p>This is [[also] untouched</p>'));

        // Valid snippet expression but does not exist as key
        $this->assertEquals('<p>This is [[also]] untouched</p>', SnippetParser::parse('<p>This is [[also]] untouched</p>'));
    }

    /** @test */
    function it_can_parse_a_value_that_contains_a_snippet_key()
    {
        $this->assertEquals('<p>This is <p>This is a snippet</p> untouched</p>', SnippetParser::parse('<p>This is [[snippet-stub]] untouched</p>'));
    }

    /** @test */
    function it_can_render_a_snippet_when_found_in_content()
    {
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validUpdatePageParams([
                'trans' => [
                    'nl' => [
                        'title' => 'foobar',
                        'slug' => 'foobar-slug',
                        'content' => 'foo [[snippet-stub]] bar',
                    ],
                ],
            ]));

        $this->assertEquals('foo <p>This is a snippet</p> bar', $this->page->fresh()->withSnippets()->content);
        $this->assertEquals('foo [[snippet-stub]] bar', $this->page->fresh()->content);
    }

    /** @test */
    function it_can_render_a_snippet_when_found_in_pagebuilder_section()
    {
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validUpdatePageParams([
                'sections.text.new' => [
                    [
                        'slug' => 'text-1',
                        'trans' => [
                            'nl' => [
                                'content' => 'foo [[snippet-stub]] bar',
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertEquals('foo <p>This is a snippet</p> bar', $this->page->fresh()->withSnippets()->renderChildren());
        $this->assertEquals('foo [[snippet-stub]] bar', $this->page->fresh()->renderChildren());
    }

    /** @test */
    function it_can_render_a_snippet_when_found_in_module_content()
    {
        $module = app(CreateModule::class)->handle('newsletter', 'new-slug');

        $this->asAdmin()
            ->put(route('chief.back.modules.update', $module->id), $this->validUpdateModuleParams([
                'trans' => [
                    'nl' => [
                        'title' => 'foobar',
                        'content' => '[[snippet-stub]]',
                    ],
                ],
            ]));

        $this->assertEquals('<p>This is a snippet</p>', $module->fresh()->withSnippets()->presentForParent($this->page));
        $this->assertEquals('[[snippet-stub]]', $module->fresh()->presentForParent($this->page));

    }
}
