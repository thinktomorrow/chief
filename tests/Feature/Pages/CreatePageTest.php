<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;

class CreatePageTest extends TestCase
{
    use PageFormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('singles', PageManager::class, Single::class);

        Route::get('pages/{slug}', function () {})->name('pages.show');
    }

    /** @test */
    public function creating_a_new_page()
    {
        $response = $this->asAdmin()
            ->post(route('chief.back.managers.store', 'singles'), $this->validPageParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.managers.edit', ['singles', Page::first()->getKey()]));

        $this->assertCount(1, Page::all());
        $this->assertNewPageValues(Page::first());
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_page()
    {
        $response = $this->post(route('chief.back.managers.store', 'singles'), $this->validPageParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, Page::all());
    }

    /** @test */
    public function when_creating_page_title_is_required_for_fallback_locale()
    {
        config()->set('app.fallback_locale', 'nl');

        $this->assertValidation(new Page(), 'trans.nl.title', $this->validPageParams(['trans.nl.title' => '']),
            route('chief.back.managers.index', 'singles'),
            route('chief.back.managers.store', 'singles')
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $page = factory(Page::class)->create([
                'title:nl'  => 'titel nl',
                'slug:nl'   => 'foobarnl'
            ]);

        $this->assertCount(1, Page::all());

        $response = $this->asAdmin()
            ->post(route('chief.back.managers.store', 'singles'), $this->validPageParams([
                    'trans.nl.title'  => 'foobarnl',
                    'trans.en.title'  => 'foobaren',
                ])
            );

        $response->assertStatus(302);

        $pages = Page::all();
        $this->assertCount(2, $pages);
        $this->assertNotEquals($pages->first()->slug, $pages->last()->slug);
    }

    /** @test */
    public function slug_must_be_unique_even_with_translations()
    {
        $page = factory(Page::class)->create([
                'title:nl'  => 'titel nl',
                'slug:nl'   => 'foobar'
            ]);

        $this->assertCount(1, Page::all());

        $response = $this->asAdmin()
            ->post(route('chief.back.managers.store', 'singles'), $this->validPageParams([
                    'trans.nl.slug'  => 'foobar',
                    'trans.en.slug'  => 'foobar',
                ])
            );
        $response->assertStatus(302);

        $pages = Page::all();
        $this->assertCount(2, $pages);
        $this->assertNotEquals($pages->first()->slug, $pages->last()->slug);
    }

    /** @test */
    public function uses_title_as_slug_if_slug_is_empty()
    {
        $response = $this->asAdmin()
            ->post(route('chief.back.managers.store', 'singles'), $this->validPageParams([
                    'trans.nl.title'    => 'foobar',
                    'trans.nl.slug'     => '',
                    'trans.en.title'    => 'foobar',
                    'trans.en.slug'     => '',
                ])
            );
        $response->assertStatus(302);

        $pages = Page::all();
        $this->assertCount(1, $pages);
        $this->assertNotNull($pages->first()->slug);
    }
}
