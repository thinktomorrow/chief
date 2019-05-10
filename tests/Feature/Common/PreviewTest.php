<?php

namespace Thinktomorrow\Chief\Tests\Feature;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Urls\UrlRecord;

class PreviewTest extends TestCase
{
    /** @test */
    public function an_admin_can_view_previews_of_draft_pages()
    {
        $this->disableExceptionHandling();

        $originalpage = factory(Page::class)->create(['published' => 0]);
        $urlRecord = UrlRecord::create([
            'model_type' => $originalpage->getMorphClass(),
            'model_id' => $originalpage->id,
            'slug' => 'foobar',
        ]);

        $response = $this->asAdminWithoutRole()->get(route('demo.pages.show', $urlRecord->slug) . '?preview-mode');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_not_view_previews_of_draft_pages()
    {
        $originalpage = factory(Page::class)->create(['published' => 0]);
        $urlRecord = UrlRecord::create([
            'model_type' => $originalpage->getMorphClass(),
            'model_id' => $originalpage->id,
            'slug' => 'foobar',
        ]);

        $response = $this->get(route('demo.pages.show', $urlRecord->slug) . '?preview-mode');

        $response->assertStatus(404);
    }
}
