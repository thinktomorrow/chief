<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class UpdatePageTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
        ]);

        $this->page = app(CreatePage::class)->handle('articles', $this->validPageParams()['trans'], [], [], []);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->asAdmin()->get(route('chief.back.pages.edit', $this->page->id))
                               ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $this->get(route('chief.back.pages.edit', $this->page->id))
             ->assertStatus(302)
             ->assertRedirect(route('chief.back.login'));

        $this->assertNewPageValues($this->page->fresh());
    }

    /** @test */
    public function it_can_edit_a_page()
    {
        $page = factory(Page::class)->create();

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams());

        $this->assertUpdatedPageValues($page->fresh());
    }

    /** @test */
    public function it_can_update_the_page_relations()
    {
        $this->markTestSkipped('Relations update is disabled in preference of the pagebuilder module logic.');

        $page = factory(Page::class)->create();
        $otherPage = factory(Page::class)->create();

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'relations' => [
                    $otherPage->flatReference()->get()
                ]
            ]));

        $this->assertCount(1, $page->fresh()->children());
        $this->assertEquals($otherPage->id, $page->fresh()->children()->first()->id);
    }

    /** @test */
    public function when_updating_page_title_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.title', $this->validUpdatePageParams(['trans.nl.title' => '']),
            route('chief.back.pages.index', 'singles'),
            route('chief.back.pages.update', $this->page->id),
            1, 'put'
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $otherPage = factory(Page::class)->create([
            'trans.nl.title'  => 'titel nl',
            'trans.nl.slug'   => 'slug-nl'
        ]);

        $this->assertValidation(new Page(), 'trans.nl.slug', $this->validUpdatePageParams(['trans.nl.slug' => 'slug-nl']),
            route('chief.back.pages.index', 'singles'),
            route('chief.back.pages.update', $this->page->id),
            2, 'put'
        );

        // Assert nothing has been updated
        $this->assertNewPageValues($this->page);
    }

    /** @test */
    public function slugcheck_takes_archived_into_account_as_well()
    {
        $otherPage = factory(Page::class)->create([
            'trans.nl.title'  => 'titel nl',
            'trans.nl.slug'   => 'slug-nl'
        ]);

        $otherPage->archive();

        $this->assertValidation(new Page(), 'trans.nl.slug', $this->validUpdatePageParams(['trans.nl.slug' => 'slug-nl']),
            route('chief.back.pages.index', 'singles'),
            route('chief.back.pages.update', $this->page->id),
            1, // Archived one is not counted
            'put'
        );

        // Assert nothing has been updated
        $this->assertNewPageValues($this->page);
    }

    /** @test */
    public function only_nl_is_required()
    {
        $response = $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validUpdatePageParams([
                'trans.en'  => [
                    'title' => '',
                    'slug' => '',
                ],
            ])
        );
        $response->assertStatus(302);

        $this->assertNull($this->page->fresh()->getTranslation('en'));
    }

    /** @test */
    public function slug_uses_title_if_its_empty()
    {
        $page = factory(Page::class)->create([
            'trans.nl.title'  => 'foobar nl',
            'trans.nl.slug'   => 'titel-nl'
        ]);

        $response = $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'trans.nl'  => [
                    'title' => 'foobar nl',
                    'slug'  => '',
                ],
            ])
        );

        $response->assertStatus(302);

        $this->assertEquals('foobar-nl', $page->fresh()->slug);
    }
}
