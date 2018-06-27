<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class CreatePageTest extends TestCase
{
    use PageFormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function creating_a_new_page()
    {
        $response = $this->asAdmin()
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.pages.edit', Page::first()->getKey()));

        $this->assertCount(1, Page::all());
        $this->assertNewPageValues(Page::first());
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_page()
    {
        $response = $this->post(route('chief.back.pages.store', 'statics'), $this->validPageParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, Page::all());
    }

    /** @test */
    public function when_creating_page_title_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.title', $this->validPageParams(['trans.nl.title' => '']),
            route('chief.back.pages.index', 'statics'),
            route('chief.back.pages.store', 'statics')
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
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams([
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
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams([
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
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams([
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

    /** @test */
    public function it_can_delete_pages()
    {
        $user = $this->developer();
        $response  =$this->actingAs($user, 'chief')
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams(['published' => false]));

        $this->assertCount(1, Page::get());

        $page = Page::first();
        $this->actingAs($user, 'chief')
             ->delete(route('chief.back.pages.destroy', $page->id), ['deleteconfirmation' => 'DELETE']);

        $this->assertCount(0, Page::get());
    }
    /** @test */
    public function it_can_archive_pages()
    {
        $user = $this->developer();
        $page = factory(Page::class)->create(['published' => true]);

        $this->assertCount(1, Page::get());

        $this->actingAs($user, 'chief')
             ->delete(route('chief.back.pages.destroy', Page::first()->id), ['deleteconfirmation' => 'DELETE']);

        $this->assertCount(0, Page::get());
        $this->assertCount(1, Page::withArchived()->get());
    }
}
