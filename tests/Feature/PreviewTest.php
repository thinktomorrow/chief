<?php

namespace Chief\Tests\Feature;

use Chief\Tests\TestCase;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Users\User;
use Illuminate\Support\Facades\Auth;
use Chief\Pages\Page;

class PreviewTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    public function an_admin_can_view_previews_of_draft_pages()
    {
        $originalpage = factory(Page::class)->create([
            'published' => 0
        ]);

        $response = $this->asDefaultAdmin()->get(route('demo.pages.show', $originalpage->slug) . '?preview-mode');

        $response->assertStatus(200);
        $response->assertViewHas('page');

        $page = $response->original->getData()['page'];

        $this->assertInstanceOf('Chief\Pages\Page', $page);
        $this->assertEquals($originalpage->slug, $page->slug);
    }

    /** @test */
    public function a_user_can_not_view_previews_of_draft_pages()
    {
        $originalpage = factory(Page::class)->create([
            'published' => 0
        ]);

        $response = $this->get(route('demo.pages.show', $originalpage->slug) . '?preview-mode');

        $response->assertStatus(302);
        $response->assertRedirect(route('demo.pages.index'));
    }
}
