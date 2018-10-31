<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;

class UpdatePageTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('singles', PageManager::class, Single::class);

        // Create a dummy page up front based on the expected validPageParams
        $this->page = Single::create([
            'title:nl' => 'new title',
            'slug:nl' => 'new-slug',
            'title:en' => 'nouveau title',
            'slug:en' => 'nouveau-slug',
        ]);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () {})->name('pages.show');
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->asAdmin()->get(route('chief.back.managers.edit', ['singles', $this->page->id]))
                               ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        auth()->guard('chief')->logout();

        $this->get(route('chief.back.managers.edit', ['singles', $this->page->id]))
             ->assertStatus(302)
             ->assertRedirect(route('chief.back.login'));

        $this->assertNewPageValues($this->page->fresh());
    }

    /** @test */
    public function it_can_edit_a_page()
    {
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams());

        $this->assertUpdatedPageValues($this->page->fresh());
    }

    /** @test */
    public function when_updating_page_title_is_required_for_fallback_locale()
    {
        config()->set('app.fallback_locale', 'nl');

        $this->assertValidation(new Page(), 'trans.nl.title', $this->validUpdatePageParams(['trans.nl.title' => '']),
            route('chief.back.managers.index', 'singles'),
            route('chief.back.managers.update', ['singles', $this->page->id]),
            1, 'put'
        );
    }

    /** @test */
    public function slug_is_forced_as_unique()
    {
        factory(Page::class)->create([
            'trans.nl.title'  => 'titel nl',
            'trans.nl.slug'   => 'slug-nl'
        ]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams(['trans.nl.slug' => 'slug-nl']))
            ->assertSessionHasNoErrors();

        $this->assertEquals('slug-nl-1', $this->page->fresh()->slug);
    }

    /** @test */
    public function slugcheck_takes_archived_into_account_as_well()
    {
        $otherPage = factory(Page::class)->create([
            'trans.nl.title'  => 'titel nl',
            'trans.nl.slug'   => 'slug-nl'
        ]);

        $otherPage->archive();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams(['trans.nl.slug' => 'slug-nl']))
            ->assertSessionHasNoErrors();

        $this->assertEquals('slug-nl-1', $this->page->fresh()->slug);
    }

    /** @test */
    public function only_fallback_locale_is_required()
    {
        config()->set('app.fallback_locale', 'nl');

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
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
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'trans.nl'  => [
                    'title' => 'foobar nl',
                    'slug'  => '',
                ],
            ])
        );

        $response->assertStatus(302);

        $this->assertEquals('foobar-nl', $page->fresh()->slug);
    }

    /** @test */
    public function slug_can_contain_slashes()
    {
        $page = factory(Page::class)->create([
            'trans.nl.title'  => 'foobar nl',
            'trans.nl.slug'   => 'titel-nl'
        ]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'trans.nl'  => [
                    'title' => 'foobar nl',
                    'slug'  => 'articles/foobar',
                ],
            ])
        );

        $this->assertEquals('articles/foobar', $page->fresh()->slug);
    }
}
