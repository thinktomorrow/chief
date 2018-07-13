<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class PageBuildTest extends TestCase
{
    use PageBuildFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'singles'    => Single::class,
            'articles'   => ArticlePageFake::class,
            'text'       => TextModule::class,
            'newsletter' => NewsletterModuleFake::class,
        ]);

        $this->page = app(CreatePage::class)->handle('articles', [
            'trans' => [
                'nl' => [
                    'title' => 'new title',
                    'slug'  => 'new-slug',
                ],
                'en' => [
                    'title' => 'nouveau title',
                    'slug'  => 'nouveau-slug',
                ],
            ],
        ], [], [], []);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () { })->name('pages.show');
    }

    /** @test */
    function it_can_add_a_text_module()
    {
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams());

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(Module::class, $this->page->children()->first());
        $this->assertInstanceOf(TextModule::class, $this->page->children()->first());
        $this->assertEquals('new content', $this->page->children()->first()->content);
    }

    /** @test */
    function it_can_replace_a_text_module()
    {
        // Add first text module
        $module = TextModule::create(['collection' => 'text', 'slug' => 'eerste-text']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [
                    [
                        'id'    => $module->id,
                        'trans' => [
                            'nl' => [
                                'content' => 'replaced content',
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertEquals('replaced content', $this->page->freshChildren()->first()->content);
    }

    /** @test */
    function it_can_remove_a_text_module()
    {
        // Add first text module
        $module = TextModule::create(['collection' => 'text', 'slug' => 'eerste-text']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [$module->id],
            ]));

        $this->assertCount(0, $this->page->children());
    }

    /** @test */
    function it_can_add_an_existing_module()
    {
        $module = NewsletterModuleFake::create(['collection' => 'newsletter', 'slug' => 'nieuwsbrief', 'content:nl' => 'newsletter content']);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    'new' => [
                        $module->flatReference()->get()
                    ],
                ],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(NewsletterModuleFake::class, $this->page->children()->first());
        $this->assertEquals('newsletter content', $this->page->children()->first()->content);
    }

    /** @test */
    function adding_existing_module_does_not_change_anything()
    {
        $module = NewsletterModuleFake::create(['collection' => 'newsletter', 'slug' => 'nieuwsbrief', 'content:nl' => 'newsletter content']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    'new' => [
                        $module->flatReference()->get()
                    ],
                ],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(NewsletterModuleFake::class, $this->page->children()->first());
        $this->assertEquals('newsletter content', $this->page->children()->first()->content);
    }

    /** @test */
    function it_can_add_pages_as_module()
    {
        $this->disableExceptionHandling();
        $article = ArticlePageFake::create(['collection' => 'articles', 'title:nl' => 'tweede artikel', 'slug:nl' => 'tweede-slug']);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    'new' => [
                        $article->flatReference()->get()
                    ],
                ],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(ArticlePageFake::class, $this->page->children()->first());
        $this->assertEquals('tweede artikel', $this->page->children()->first()->title);
    }

    /** @test */
    function it_can_remove_modules()
    {
        $module = NewsletterModuleFake::create(['collection' => 'newsletter', 'slug' => 'nieuwsbrief', 'content:nl' => 'newsletter content']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    'remove' => [
                        $module->flatReference()->get()
                    ],
                ],
            ]));

        $this->assertCount(0, $this->page->children());
    }
}