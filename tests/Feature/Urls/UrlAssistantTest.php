<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;

class UrlAssistantTest extends TestCase
{
    /** @var Manager */
    private $manager;

    public function setUp(): void
    {
        parent::setUp();

        Product::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        app(Register::class)->register('products', ProductManagerWithUrlAssistant::class, Product::class);

        $this->manager = app(Managers::class)->findByKey('products');

        $this->setUpDefaultAuthorization();

        Route::get('{slug}', function () {
            return ChiefResponse::fromRequest();
        })->name('pages.show');
    }

    /** @test */
    function it_adds_an_url_on_creation()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $createdModel = Product::first();

        $this->assertEquals(get_class($createdModel), UrlRecord::findBySlug('foobar', 'nl')->model_type);
        $this->assertEquals($createdModel->id, UrlRecord::findBySlug('foobar', 'nl')->model_id);
    }

    /** @test */
    function it_can_add_one_url_for_all_locales()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [UrlAssistant::WILDCARD => 'foobar'],
        ]);

        $createdModel = Product::first();
        $urlRecord = UrlRecord::findBySlug('foobar', 'nl');

        $this->assertNull($urlRecord->locale);
        $this->assertEquals(get_class($createdModel), $urlRecord->model_type);
        $this->assertEquals($createdModel->id, $urlRecord->model_id);
    }

    /** @test */
    function it_can_add_an_url_for_each_locale()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [
                'nl' => 'foobar-nl',
                'fr' => 'foobar-fr',
            ],
        ]);

        $this->assertEquals('foobar-nl', UrlRecord::findBySlug('foobar-nl', 'nl')->slug);
        $this->assertEquals('foobar-fr', UrlRecord::findBySlug('foobar-fr', 'fr')->slug);
    }

    /** @test */
    function it_cannot_add_same_url_for_specific_locale_twice()
    {
        $this->asAdmin()->get($this->manager->route('create'));
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $this->assertTrue(UrlRecord::exists('nl','foobar'));

        $response = $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect($this->manager->route('create'));
        $response->assertSessionHasErrors('url-slugs');
        $this->assertCount(1, UrlRecord::all());
    }

    /** @test */
    function it_can_use_same_url_for_same_model()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $product = Product::first();
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertEquals(1, UrlRecord::count());
        // Assert url is updated
        $this->assertEquals('foobar', UrlRecord::findByModel($product, 'nl')->slug);
        $this->assertFalse(UrlRecord::findByModel($product, 'nl')->isRedirect());
    }

    /** @test */
    function it_cannot_use_same_url_for_different_model()
    {
        $this->asAdmin()->post($this->manager->route('store'), ['url-slugs' => ['nl' => 'foobar'],]);

        $product = Product::create();

        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('url-slugs');
        $this->assertCount(1, UrlRecord::all());
    }

    /** @test */
    function when_updating_an_url_it_keeps_the_old_url_as_redirect()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $product = Product::first();
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => ['nl' => 'foobar-updated'],
        ]);

        $this->assertEquals(2, UrlRecord::count());
        // Assert url is updated
        $this->assertEquals('foobar-updated', UrlRecord::findByModel($product, 'nl')->slug);
        $this->assertFalse(UrlRecord::findByModel($product, 'nl')->isRedirect());

        // Assert old one is now set as redirect
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->get(route('pages.show','foobar'))->assertRedirect('foobar-updated');
    }

    /** @test */
    function updating_to_same_url_as_a_redirect_one_of_same_model_will_remove_redirect()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');

        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        // Update back to original value
        $response = $this->asAdmin()->put($this->manager->manage(Product::first())->route('update'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertCount(2,UrlRecord::all());
        $this->assertFalse(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());
    }

    /** @test */
    function updating_to_same_url_as_a_redirect_of_different_model_will_fail_validation()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        $response = $this->createAndChangeUrlSlug('other','foobar');

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertCount(3,UrlRecord::all()); // And not 3 - so we know the new one is not added

        // verify url still points to old model
        $this->assertEquals(1, UrlRecord::findBySlug('foobar', 'nl')->model_id);
        $this->assertFalse(UrlRecord::findBySlug('other', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
    }

    /** @test */
    function when_archiving_a_model_it_gives_the_option_to_redirect_the_archived_url()
    {

    }

    /** @test */
    function it_can_store_the_archived_url_as_redirect()
    {

    }

    /** @test */
    function it_removes_the_archived_url_when_not_set_as_redirect()
    {

    }
    
    /** @test */
    function it_cannot_publish_without_url()
    {
        
    }

    /** @test */
    function a_non_unique_url_is_halted()
    {
        // evt. instant feedback?
    }

    /**
     * @param string $from
     * @param string $to
     * @return TestResponse
     */
    private function createAndChangeUrlSlug($from = 'foobar', $to = 'foobar-updated'): TestResponse
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => $from],
        ]);

        // Get the last inserted model
        $product = Product::orderBy('id','DESC')->first();

        // We change it to another value
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => ['nl' => $to],
        ]);

        return $response;
    }

}

class ProductManagerWithUrlAssistant extends ManagerFake
{
    protected $assistants = [
        'url' => UrlAssistant::class,
    ];
}

class Product extends ManagedModelFake implements ProvidesUrl
{
    public $translationModel = ManagedModelFakeTranslation::class;
    public $translationForeignKey = 'managed_model_fake_id';

    public function url($locale = null): string
    {
        if(!$locale) $locale = app()->getLocale();

        return UrlRecord::findByModel($this, $locale)->slug;
    }

    public function previewUrl($locale = null): string
    {
        return $this->url($locale);
    }

    public static function baseUrlSegment($locale = null): string
    {
        return '/';
    }
}
