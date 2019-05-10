<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\UrlSlugFields;

class PageUrlTest extends TestCase
{
    use PageFormParams;

    /** @var Manager */
    private $manager;

    public function setUp(): void
    {
        parent::setUp();

        app(Register::class)->register('products', PageManager::class, ProductWithBaseSegments::class);

        $this->manager = app(Managers::class)->findByKey('products');

        $this->setUpDefaultAuthorization();

        Route::get('{slug}', function ($slug) {
            return ChiefResponse::fromSlug($slug);
        })->name('pages.show');
    }

    /** @test */
    function the_fixed_base_segment_is_prepended_to_the_slug()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]));

        $model = ProductWithBaseSegments::first();

        app()->setLocale('nl');
        $this->assertEquals(url('/producten/foobar'), $model->url() );
        $this->assertEquals(url('/producten/foobar'), $model->url('nl') );
        $this->assertEquals(url('/products/foobar'), $model->url('en') );
    }

    /** @test */
    function when_having_a_general_url_a_page_can_still_have_a_localised_base_segment()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                UrlAssistant::WILDCARD => 'foobar',
            ],
        ]));

        $model = ProductWithBaseSegments::first();

        $this->assertEquals(url('/producten/foobar'), $model->url('nl') );
        $this->assertEquals(url('/products/foobar'), $model->url('en') );
    }

    /** @test */
    function url_is_by_default_based_on_current_locale()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]));

        $model = ProductWithBaseSegments::first();

        app()->setLocale('nl');
        $this->assertEquals(url('/producten/foobar'), $model->url() );

        app()->setLocale('en');
        $this->assertEquals(url('/products/foobar'), $model->url() );
    }
}
