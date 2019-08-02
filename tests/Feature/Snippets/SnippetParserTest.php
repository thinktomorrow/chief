<?php

namespace Thinktomorrow\Chief\Tests\Feature\Snippets;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Snippets\SnippetParser;
use Thinktomorrow\Chief\Snippets\SnippetCollection;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageManager;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Modules\ModuleFormParams;

class SnippetParserTest extends TestCase
{
    use PageFormParams,
        ModuleFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
        config()->set('app.fallback_locale', 'nl');

        /** @var Register */
        app(Register::class)->register(ArticlePageManager::class, ArticlePageFake::class);
        app(Register::class)->register(ModuleManager::class, NewsletterModuleFake::class);

        // Default not enable snippet loading
        $this->app['config']->set('thinktomorrow.chief.withSnippets', false);

        $this->app['config']->set('thinktomorrow.chief.loadSnippetsFrom', [
            realpath(__DIR__.'/snippet-stub.html'),
        ]);

        SnippetCollection::refresh();

        Route::get('pages/{slug}', function () {
        })->name('pages.show');

        Route::get('articles/{slug}', function () {
        })->name('articles.show');
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
        $this->disableExceptionHandling();
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

        $this->assertEquals('<p>This is a snippet</p>', $module->fresh()->withSnippets()->setViewParent($page)->renderView());
        $this->assertEquals('[[snippet-stub]]', $module->fresh()->setViewParent($page)->renderView());
    }

    /** @test */
    public function it_can_enable_snippet_loading_by_default()
    {
        $this->app['config']->set('thinktomorrow.chief.withSnippets', true);

        $page = $this->addSnippetToPageSection();
        $this->assertEquals('foo <p>This is a snippet</p> bar', $page->fresh()->renderChildren());

        $module = $this->addSnippetToModule();
        $this->assertEquals('<p>This is a snippet</p>', $module->fresh()->setViewParent($page)->renderView());
    }

    private function addSnippetToPageContent()
    {
        $page = ArticlePageFake::create();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $page->id]), $this->validUpdatePageParams([
                'trans' => [
                    'nl' => [
                        'title' => 'foobar',
                        'internal_title' => 'foobar-slug',
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
            ->put(route('chief.back.managers.update', ['articles_fake', $page->id]), $this->validUpdatePageParams([
                'sections.text.new' => [
                    [
                        'internal_title' => 'text-1',
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
        $module = NewsletterModuleFake::create(['internal_title' => 'new-slug']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['newsletters_fake', $module->id]), $this->validUpdateModuleParams([
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
