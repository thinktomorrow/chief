<?php

namespace Thinktomorrow\Chief\Tests\Feature\Snippets;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Pages\PageManager;
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

        /** @var Register */
        app(Register::class)->register('articles', PageManager::class, ArticlePageFake::class);
        app(Register::class)->register('newsletters', ModuleManager::class, NewsletterModuleFake::class);

        // Default not enable snippet loading
        $this->app['config']->set('thinktomorrow.chief.withSnippets', false);

        $this->app['config']->set('thinktomorrow.chief.loadSnippetsFrom', [
            realpath(__DIR__.'/snippet-stub.html'),
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
    public function it_can_render_a_snippet_when_found_in_content()
    {
        $page = $this->addSnippetToPageContent();

        $this->assertEquals('foo <p>This is a snippet</p> bar', $page->fresh()->withSnippets()->content);
        $this->assertEquals('foo [[snippet-stub]] bar', $page->fresh()->content);
    }

    /** @test */
    public function it_can_render_a_snippet_when_found_in_pagebuilder_section()
    {
        $page = $this->addSnippetToPageSection();

        $this->assertEquals('foo <p>This is a snippet</p> bar', $page->fresh()->withSnippets()->renderChildren());
        $this->assertEquals('foo [[snippet-stub]] bar', $page->fresh()->renderChildren());
    }

    /** @test */
    public function it_can_render_a_snippet_when_found_in_module_content()
    {
        $this->disableExceptionHandling();
        $page = ArticlePageFake::create();
        $module = $this->addSnippetToModule();

        $this->assertEquals('<p>This is a snippet</p>', $module->fresh()->withSnippets()->presentForParent($page));
        $this->assertEquals('[[snippet-stub]]', $module->fresh()->presentForParent($page));
    }

    /** @test */
    public function it_can_enable_snippet_loading_by_default()
    {
        $this->app['config']->set('thinktomorrow.chief.withSnippets', true);

        $page = $this->addSnippetToPageSection();
        $this->assertEquals('foo <p>This is a snippet</p> bar', $page->fresh()->renderChildren());

        $module = $this->addSnippetToModule();
        $this->assertEquals('<p>This is a snippet</p>', $module->fresh()->presentForParent($page));
    }

    private function addSnippetToPageContent()
    {
        $page = ArticlePageFake::create();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles', $page->id]), $this->validUpdatePageParams([
                'trans' => [
                    'nl' => [
                        'title' => 'foobar',
                        'slug' => 'foobar-slug',
                        'content' => 'foo [[snippet-stub]] bar',
                    ],
                ],
            ]));

        return $page;
    }

    private function addSnippetToPageSection()
    {
        $page = ArticlePageFake::create();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles', $page->id]), $this->validUpdatePageParams([
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

        return $page;
    }

    private function addSnippetToModule()
    {
        $module = NewsletterModuleFake::create(['slug' => 'new-slug']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['newsletters', $module->id]), $this->validUpdateModuleParams([
                'trans' => [
                    'nl' => [
                        'title' => 'foobar',
                        'content' => '[[snippet-stub]]',
                    ],
                ],
            ]));

        return $module;
    }
}
