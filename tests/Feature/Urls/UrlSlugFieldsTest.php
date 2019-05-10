<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Urls\UrlSlugFields;

class UrlSlugFieldsTest extends TestCase
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
    function the_fixed_base_segment_is_shown_as_prepend_for_records_without_base_segment()
    {
        // Force create the url record without base segment
        $model = ProductWithBaseSegments::create();
        UrlRecord::create(['locale' =>'nl', 'slug' => 'foobar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);
        UrlRecord::create(['locale' =>'en', 'slug' => 'foobar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $fields = UrlSlugFields::fromModel($model);

        $this->assertCount(2, $fields->all());

        $nlField = $fields['url-slugs.nl'];
        $enField = $fields['url-slugs.en'];

        $this->assertStringEndsWith('producten/', $nlField->prepend);
        $this->assertStringEndsWith('products/', $enField->prepend);

        $this->assertEquals('foobar', $nlField->value());
        $this->assertEquals('foobar', $enField->value());
    }

    /** @test */
    function the_fixed_base_segment_is_removed_from_slug_for_form_display()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]));

        $model = ProductWithBaseSegments::first();

        $fields = UrlSlugFields::fromModel($model);

        $this->assertCount(2, $fields->all());

        $nlField = $fields['url-slugs.nl'];
        $enField = $fields['url-slugs.en'];

        $this->assertStringEndsWith('producten/', $nlField->prepend);
        $this->assertStringEndsWith('products/', $enField->prepend);

        $this->assertEquals('foobar', $nlField->value());
        $this->assertEquals('foobar', $enField->value());
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

    /** @test */
    function it_should_not_present_redirects()
    {

    }
}


