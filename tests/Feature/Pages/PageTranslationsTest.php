<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;

class PageTranslationsTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('singles', PageManager::class, Single::class);

        // Create a dummy page up front based on the expected validPageParams
        $this->page = Single::create([
            'title:nl' => 'new title',
            'title:en' => 'nouveau title',
        ]);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () {

        })->name('pages.show');
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
}
