<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\MediaModule;

class UpdatePageTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpChiefEnvironment();

        app(Register::class)->register(PageManager::class, Single::class);

        // Create a dummy page up front based on the expected validPageParams
        $this->page = Single::create([
            'title:nl' => 'new title',
            'title:en' => 'nouveau title',
        ]);

        UrlRecord::create([
            'locale' => 'nl',  'slug' => 'new-slug', 'model_type' => $this->page->getMorphClass(), 'model_id' => $this->page->id,
        ]);

        UrlRecord::create([
            'locale' => 'en',  'slug' => 'nouveau-slug', 'model_type' => $this->page->getMorphClass(), 'model_id' => $this->page->id,
        ]);
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        app(Register::class)->register(ModuleManager::class, MediaModule::class, ['module']);

        $response = $this->asAdmin()->get(route('chief.back.managers.edit', ['singles', $this->page->id]));
        $response->assertViewIs('chief::back.managers.edit');
        $response->assertStatus(200);
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
    public function only_fallback_locale_is_required()
    {
        config()->set('app.fallback_locale', 'nl');

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'trans.en'  => [
                    'title' => '',
                ],
            ])
        );

        $response->assertStatus(302);

        $this->assertNull($this->page->fresh()->getTranslation('en'));
    }

    /** @test */
    function emptying_all_fields_of_a_translation_removes_the_translation()
    {
        $this->markTestIncomplete();
    }
}
