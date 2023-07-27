<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\AdminToast\Tests;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

class AdminToastTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $model = $this->setupAndCreateArticle(['title' => 'Foobar', 'current_state' => PageState::published]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);
    }

    /** @test */
    public function it_wil_not_fetch_toast_when_not_logged_in_as_admin()
    {
        $response = $this->get(route('chief.toast.get'));

        $response->assertSuccessful()
            ->assertJson(['data' => null]);
    }

    /** @test */
    public function it_can_fetch_toast()
    {
        $response = $this->asAdmin()->get(route('chief.toast.get') . '?path=foo/bar&locale=nl');

        $response->assertSuccessful();
        //            ->assertJson(['data' => view('chief-site::admin-toast-element')->render()]);
    }

    /** @test */
    public function it_returns_nothing_when_something_went_wrong()
    {
    }

    /** @test */
    public function it_can_toggle_preview_mode()
    {
        $this->assertNull(session('preview-mode'));
        $response = $this->asAdmin()->get(route('chief.toast.toggle'));

        $response->assertRedirect();

        $this->assertTrue(session('preview-mode'));
    }
}
