<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Sets\SetReference;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Sets\StoredSetReference;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Feature\Sets\DummySetRepository;

class PageSetTest extends TestCase
{
    use PageBuildFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
        config()->set('app.fallback_locale', 'nl');

        app(Register::class)->register(PageManager::class, ArticlePageFake::class);

        $this->app['config']->set('thinktomorrow.chief.sets', [
            'foobar'   => [
                'action' => DummySetRepository::class.'@all',
            ],
        ]);

        // Create a dummy page up front based on the expected validPageParams
        $this->page = ArticlePageFake::create([
            'title:nl' => 'new title',
            'title:en' => 'nouveau title',
        ]);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function it_can_add_a_pageset()
    {
        $pageset_ref = (new SetReference('foobar', DummySetRepository::class.'@all', [5], 'foobar'));

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.pagesets'      => [
                    $pageset_ref->flatReference()->get()
                ],
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(StoredSetReference::class, $this->page->children()->first());
    }

    /** @test */
    public function it_can_keep_an_already_stored_pageset()
    {
        $stored_pageset_ref = (new SetReference('foobar', DummySetRepository::class.'@all', [5], 'foobar'))->store();
        $this->page->adoptChild($stored_pageset_ref, ['sort' => 0]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
                'sections.pagesets'      => [
                    $stored_pageset_ref->flatReference()->get()
                ],
                'sections.text.new'     => [],
                'sections.text.replace' => [],
                'sections.text.remove'  => [],
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(StoredSetReference::class, $this->page->children()->first());
    }

    /** @test */
    public function it_can_remove_a_pageset()
    {
        $stored_pageset_ref = (new SetReference('foobar', DummySetRepository::class.'@all', [5], 'foobar'))->store();
        $this->page->adoptChild($stored_pageset_ref, ['sort' => 0]);

        $this->assertCount(1, $this->page->fresh()->children());

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_fake', $this->page->id]), $this->validPageParams([
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
