<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceCollection;

class PageBuildTest extends TestCase
{
    use PageBuildFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
        config()->set('app.fallback_locale', 'nl');

        app(Register::class)->register(PageManager::class, ArticlePageFake::class);

        // Create a dummy page up front based on the expected validPageParams
        $this->page = ArticlePageFake::create([
            'title:nl' => 'new title',
            'title:en' => 'nouveau title',
        ]);

        $this->app['config']->set('thinktomorrow.chief.relations.children', [
            Module::class,
        ]);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function it_can_fetch_all_sections_in_order()
    {
        $module    = TextModule::create(['internal_title' => 'eerste-text', 'content:nl' => 'eerste text']);
        $otherPage = ArticlePageFake::create(['title:nl' => 'artikel title', 'content:nl' => 'article text', 'published' => true]);
        $module2   = TextModule::create(['internal_title' => 'tweede-text', 'content:nl' => 'tweede text']);
        $module3   = NewsletterModuleFake::create(['internal_title' => 'newsletter', 'content:nl' => 'nieuwsbrief']);

        $this->page->adoptChild($module, ['sort' => 0]);
        $this->page->adoptChild($module2, ['sort' => 2]);
        $this->page->adoptChild($otherPage, ['sort' => 1]);
        $this->page->adoptChild($module3, ['sort' => 5]);

        $this->assertCount(4, $this->page->children());
        $this->assertCount(4, $this->page->presentChildren());

        $this->assertEquals('eerste textarticle texttweede textnieuwsbrief', $this->page->renderChildren());
    }

    /** @test */
    public function it_can_fetch_all_sections_with_multiple_pages_in_order()
    {
        $module    = TextModule::create(['internal_title' => 'eerste-text', 'content:nl' => 'eerste text']);
        $otherPage = ArticlePageFake::create(['title:nl' => 'artikel title', 'content:nl' => 'article text', 'published' => true]);
        $thirdPage = ArticlePageFake::create(['title:nl' => 'artikel title', 'content:nl' => 'article text', 'published' => true]);
        $module2   = TextModule::create(['internal_title' => 'tweede-text', 'content:nl' => 'tweede text']);
        $module3   = NewsletterModuleFake::create(['internal_title' => 'newsletter', 'content:nl' => 'nieuwsbrief']);

        $this->page->adoptChild($module, ['sort' => 0]);
        $this->page->adoptChild($module2, ['sort' => 2]);
        $this->page->adoptChild($otherPage, ['sort' => 1]);
        $this->page->adoptChild($thirdPage, ['sort' => 4]);
        $this->page->adoptChild($module3, ['sort' => 5]);

        $this->assertCount(5, $this->page->children());
        $this->assertCount(5, $this->page->presentChildren());

        // Modules show their content by default but pages do not since this is not expected behaviour
        $this->assertEquals('eerste textarticle texttweede textarticle textnieuwsbrief', $this->page->renderChildren());
    }

    /** @test */
    public function all_types_are_grouped_together_but_only_when_sorted_directly_after_each_other()
    {
        $page2 = ArticlePageFake::create(['title:nl' => 'artikel title', 'content:nl' => 'article-text-1', 'published' => true]);
        $page3 = ArticlePageFake::create(['title:nl' => 'artikel title', 'content:nl' => 'article-text-2', 'published' => true]);
        $module = TextModule::create(['internal_title' => 'tweede-text', 'content:nl' => 'module-text']);
        $page4 = ArticlePageFake::create(['title:nl' => 'artikel title', 'content:nl' => 'article-text-3', 'published' => true]);
        $this->page->adoptChild($page2, ['sort' => 1]);
        $this->page->adoptChild($page3, ['sort' => 2]);
        $this->page->adoptChild($module, ['sort' => 3]);
        $this->page->adoptChild($page4, ['sort' => 4]);
        $this->assertCount(4, $this->page->children());
        $this->assertCount(3, $this->page->presentChildren());
        $this->assertEquals(collect([
            'article-text-1article-text-2',
            'module-text',
            'article-text-3',
        ]), $this->page->presentChildren());
        $this->assertEquals('article-text-1article-text-2module-textarticle-text-3', $this->page->renderChildren());
    }


    /** @test */
    public function it_can_add_a_text_module()
    {
        config()->set('app.fallback_locale', 'nl');
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new' => [
                    [
                        'internal_title' => 'text-1',
                        'trans' => [
                            'nl' => [
                                'content' => 'new content',
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(Module::class, $this->page->children()->first());
        $this->assertInstanceOf(TextModule::class, $this->page->children()->first());
        $this->assertEquals('new content', $this->page->children()->first()->content);
    }

    /** @test */
    public function it_can_replace_a_text_module()
    {
        // Add first text module
        $module = TextModule::create(['internal_title' => 'eerste-text']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [
                    [
                        'id'    => $module->flatReference()->get(),
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
    public function it_removes_a_text_module_when_its_completely_empty()
    {
        // Add first text module
        $module = TextModule::create(['internal_title' => 'eerste-text']);
        $this->page->adoptChild($module, ['sort' => 0]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [
                    [
                        'id'    => $module->flatReference()->get(),
                        'trans' => [
                            'nl' => [
                                'content' => '  ',
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(0, $this->page->children());

        // Module is also deleted
        $this->assertNull(Module::find($module->id));
        $this->assertEquals($module->id, Module::withTrashed()->find($module->id)->id);
    }

    /** @test */
    public function it_removes_a_text_module_when_it_only_contains_empty_paragraph_tag()
    {
        // Add first text module
        $module = TextModule::create(['internal_title' => 'eerste-text']);
        $this->page->adoptChild($module, ['sort' => 0]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [
                    [
                        'id'    => $module->flatReference()->get(),
                        'trans' => [
                            'nl' => [
                                'content' => '<p><br></p>',
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(0, $this->page->children());
    }

    /** @test */
    public function it_does_not_remove_a_text_module_when_its_not_completely_empty()
    {
        // Add first text module
        $module = TextModule::create(['internal_title' => 'eerste-text']);
        $this->page->adoptChild($module, ['sort' => 0]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [
                    [
                        'id'    => $module->flatReference()->get(),
                        'trans' => [
                            'nl' => [
                                'content' => '',
                            ],
                            'fr' => [
                                'content' => 'hi'
                            ],
                        ]
                    ]
                ],
            ]));

        $this->assertCount(1, $this->page->children());
    }

    /** @test */
    public function it_can_add_an_existing_module()
    {
        $module = NewsletterModuleFake::create(['internal_title' => 'nieuwsbrief', 'content:nl' => 'newsletter content']);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    $module->flatReference()->get()
                ],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(NewsletterModuleFake::class, $this->page->children()->first());
        $this->assertEquals('newsletter content', $this->page->children()->first()->content);
    }

    /** @test */
    public function adding_existing_module_does_not_change_anything()
    {
        $module = NewsletterModuleFake::create(['internal_title' => 'nieuwsbrief', 'content:nl' => 'newsletter content']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    $module->flatReference()->get()
                ],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(NewsletterModuleFake::class, $this->page->children()->first());
        $this->assertEquals('newsletter content', $this->page->children()->first()->content);
    }

    /** @test */
    public function it_can_add_pages_as_module()
    {
        $article = ArticlePageFake::create(['title:nl' => 'tweede artikel']);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    $article->flatReference()->get()
                ],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(ArticlePageFake::class, $this->page->children()->first());
        $this->assertEquals('tweede artikel', $this->page->children()->first()->title);
    }

    /** @test */
    public function it_can_remove_modules()
    {
        $module = NewsletterModuleFake::create(['internal_title' => 'nieuwsbrief', 'content:nl' => 'newsletter content']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    // Removing module by not including them in the listing
                ],
            ]));

        $this->assertCount(0, $this->page->children());
    }

    /** @test */
    public function it_can_set_the_order()
    {
        $text_module = TextModule::create(['internal_title' => 'eerste-text', 'content:nl' => 'eerste text']);
        $otherPage = ArticlePageFake::create(['title:nl' => 'artikel title', 'content:nl' => 'article text']);
        $newsletter = NewsletterModuleFake::create(['internal_title' => 'tweede-text', 'content:nl' => 'tweede text']);

        $this->page->adoptChild($text_module, ['sort' => 0]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [
                    [
                        'internal_title' => 'text-1',
                        'trans' => [
                            'nl' => [
                                'content' => 'new text',
                            ]
                        ]
                    ],
                ],
                'sections.text.replace' => [
                    [
                        'id' => $text_module->flatReference()->get(),
                        'trans' => [
                            'nl' => [
                                'content' => 'replaced module',
                            ]
                        ]
                    ],
                ],
                'sections.text.remove'  => [],
                'sections.modules'      => [
                    $otherPage->flatReference()->get(),
                    $newsletter->flatReference()->get(),
                ],
                'sections.order' => [
                    $otherPage->flatReference()->get(),
                    $newsletter->flatReference()->get(),
                    'text-1',
                    $text_module->flatReference()->get(),
                ]
            ]));

        $this->assertCount(4, $this->page->children());

        $this->assertEquals([
            $otherPage->flatReference()->get(),
            $newsletter->flatReference()->get(),
            TextModule::findByInternalTitle('text-1')->flatReference()->get(),
            $text_module->flatReference()->get()],
            (new FlatReferenceCollection($this->page->children()->all()))->toFlatReferences()->all());
    }

    /** @test */
    public function it_only_has_general_or_own_modules()
    {
        $module = Module::create(['internal_title' => 'foobar']);
        $module->page_id = $this->page->id++;
        $module->save();

        $module = Module::create(['internal_title' => 'foobar 2']);
        $module->page_id = $this->page->id;
        $module->save();
        $managers = app(Managers::class);
        $pagebuilderField = $managers->findByKey('articles_fake')->manage($this->page)->fields()->first();

        $this->assertCount(1, $pagebuilderField->availableModules);
    }
}
