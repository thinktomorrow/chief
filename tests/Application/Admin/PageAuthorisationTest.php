<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class PageAuthorisationTest extends ChiefTestCase
{
    private ArticlePage $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticle();
    }

    /** @test */
    public function guests_cannot_view_the_create_form()
    {
        $manager = app(Registry::class)->manager(ArticlePage::managedModelKey());

        $this->get($manager->route('create'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function a_non_admin_cannot_update_a_page()
    {
        $manager = app(Registry::class)->manager(ArticlePage::managedModelKey());

        $this->page->title = 'existing-title';
        $this->page->save();

        $this->put($manager->route('update', $this->page), [
            'title' => 'nieuwe titel',
        ])->assertStatus(302)
          ->assertRedirect(route('chief.back.login'));

        $this->assertEquals('existing-title', $this->page->fresh()->title);
    }
}
