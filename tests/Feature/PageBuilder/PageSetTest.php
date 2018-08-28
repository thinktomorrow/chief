<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Modules\PagetitleModule;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\PageSets\PageSetReference;
use Thinktomorrow\Chief\PageSets\StoredPageSetReference;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Feature\PageSets\DummyPageSetRepository;
use Thinktomorrow\Chief\Tests\TestCase;

class PageSetTest extends TestCase
{
    use PageBuildFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'articles'   => ArticlePageFake::class,
        ]);

        $this->app['config']->set('thinktomorrow.chief.pagesets', [
            'foobar'   => [
                'action' => DummyPageSetRepository::class.'@all',
            ],
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
        Route::get('pages/{slug}', function () {})->name('pages.show');
    }

    /** @test */
    public function it_can_add_a_pageset()
    {
        $pageset_ref = (new PageSetReference('foobar',DummyPageSetRepository::class.'@all', [5], 'foobar'));

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.pagesets'      => [
                    $pageset_ref->flatReference()->get()
                ],
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(StoredPageSetReference::class, $this->page->children()->first());
    }

    /** @test */
    public function it_can_keep_an_already_stored_pageset()
    {
        $stored_pageset_ref = (new PageSetReference('foobar',DummyPageSetRepository::class.'@all', [5], 'foobar'))->store();
        $this->page->adoptChild($stored_pageset_ref, ['sort' => 0]);

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.pagesets'      => [
                    $stored_pageset_ref->flatReference()->get()
                ],
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(StoredPageSetReference::class, $this->page->children()->first());
    }

    /** @test */
    public function it_can_remove_a_pageset()
    {
        $stored_pageset_ref = (new PageSetReference('foobar',DummyPageSetRepository::class.'@all', [5], 'foobar'))->store();
        $this->page->adoptChild($stored_pageset_ref, ['sort' => 0]);

        $this->assertCount(1, $this->page->fresh()->children());

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
                'sections.pagesets'      => [
                    // Removing pageset by not including them in the listing
                ],
            ]));

        $this->assertCount(0, $this->page->fresh()->children());
    }
}
