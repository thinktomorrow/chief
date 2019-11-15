<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestResponse;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductFake;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductFakeWithBaseSegments;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductManagerWithUrlAssistant;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductWithBaseSegments;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;

class UrlAssistantTest extends TestCase
{
    /** @var Manager */
    private $manager;

    use PageFormParams;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpChiefEnvironment();

        ProductFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        app(Register::class)->register(ProductManagerWithUrlAssistant::class, ProductFake::class);

        $this->manager = app(Managers::class)->findByKey('products');
    }

    /** @test */
    function it_automatically_adds_an_url_on_creation()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $createdModel = ProductFake::first();

        $this->assertEquals(get_class($createdModel), UrlRecord::findBySlug('foobar', 'nl')->model_type);
        $this->assertEquals($createdModel->id, UrlRecord::findBySlug('foobar', 'nl')->model_id);
    }

    /** @test */
    function it_adds_a_slug_for_each_locale()
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
    function the_updated_slug_is_prepended_with_the_basesegment()
    {
        app(Register::class)->register(PageManager::class, ProductWithBaseSegments::class);
        $manager = app(Managers::class)->findByKey('products_with_base');

        $this->asAdmin()->post($manager->route('store'), $this->validPageParams([
            'url-slugs' => ['nl' => 'foobar'],
        ]));

        $urlRecordNl = UrlRecord::findBySlug('producten/foobar', 'nl');
        $urlRecordEn = UrlRecord::findBySlug('products/nouveau-title', 'en');

        $this->assertEquals(2, UrlRecord::count());
        $this->assertEquals('nl', $urlRecordNl->locale);
        $this->assertEquals('en', $urlRecordEn->locale);
    }

    /** @test */
    function it_cannot_add_same_url_for_specific_locale_twice()
    {
        $this->asAdmin()->get($this->manager->route('create'));
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $this->assertTrue(UrlRecord::exists('foobar', 'nl'));

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

        $product = ProductFake::first();
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), $this->validUpdatePageParams([
            'url-slugs' => ['nl' => 'foobar'],
        ]));

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

        $product = ProductFake::create();

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

        $product = ProductFake::first();
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
        $response = $this->asAdmin()->put($this->manager->manage(ProductFake::first())->route('update'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertCount(2,UrlRecord::all());
        $this->assertFalse(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());
    }

    /** @test */
    function when_updating_an_url_to_empty_string()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => 'foobar'],
        ]);

        $product = ProductFake::first();
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
    function updating_to_an_already_existing_url_will_fail_validation()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        $response = $this->createAndChangeUrlSlug('other','foobar-updated');

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertCount(3,UrlRecord::all());

        // verify url points to new model
        $this->assertEquals(1, UrlRecord::findBySlug('foobar', 'nl')->model_id);
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('other', 'nl')->isRedirect());
    }

    /** @test */
    function updating_to_same_url_as_a_redirect_of_different_model_will_remove_redirect()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        $response = $this->createAndChangeUrlSlug('other','foobar');

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertCount(3,UrlRecord::all()); // And not 4 - so we know the redirect is removed

        // verify url points to new model
        $this->assertEquals(2, UrlRecord::findBySlug('foobar', 'nl')->model_id);
        $this->assertFalse(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('other', 'nl')->isRedirect());
    }

    /** @test */
    function updating_to_empty_url_will_remove_record_and_all_redirects()
    {
        $this->createAndChangeUrlSlug('foobar','');

        $this->assertCount(0,UrlRecord::all());
    }

    /** @test */
    function updating_to_same_url_will_keep_records_as_they_were()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');

        $record = UrlRecord::findBySlug('foobar-updated', 'nl');

        $product = ProductFake::orderBy('id','DESC')->first();
        $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => ['nl' => 'foobar-updated'],
        ]);

        $this->assertCount(2,UrlRecord::all());

        // assert the record in db is still the same row
        $this->assertEquals($record->id, UrlRecord::findBySlug('foobar-updated', 'nl')->id);
    }

    /** @test */
    function updating_slug_to_empty_string_removes_it()
    {
        config()->set('translatable.locales',['nl','fr']);
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [
                'nl' => 'foobar-nl',
                'fr' => 'foobar-fr',
            ],
        ]);
        $this->assertCount(2,UrlRecord::all());

        $product = ProductFake::orderBy('id','DESC')->first();
        $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => [
                'nl' => '',
                'fr' => '',
            ],
        ]);

        $this->assertCount(0,UrlRecord::all());
    }

    /** @test */
    function it_can_update_to_root_slug()
    {
        $this->createAndChangeUrlSlug('foobar','/');

        $this->assertCount(2,UrlRecord::all());
        $this->assertNotNull(UrlRecord::findBySlug('/', 'nl'));
    }

    /** @test */
    function it_can_store_the_archived_url_as_redirect()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');
        $product = ProductFake::orderBy('id','DESC')->first();

        $this->createAndChangeUrlSlug('foobar-2','foobar-updated-2');
        $product2 = ProductFake::orderBy('id','DESC')->first();

        $response = $this->asAdmin()
            ->post(route('chief.back.assistants.archive', ['products', $product->id]), [
                'redirect_id' => $product2->flatReference()->get(),
            ]);

        $this->assertEquals($product2->id, UrlRecord::findBySlug('foobar-updated', 'nl')->redirectTo()->model_id);
        $this->assertEquals($product2->id, UrlRecord::findBySlug('foobar-updated-2', 'nl')->model_id);
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
        $this->createAndChangeUrlSlug('foobar','foobar-updated');
        $product = ProductFake::orderBy('id','DESC')->first();

        $response = $this->createAndChangeUrlSlug('foobar-2','foobar-updated');
        $product2 = ProductFake::orderBy('id','DESC')->first();

        $response->assertSessionHasErrors('url-slugs');

        $this->assertEquals('foobar-2', $product2->url());
    }

    /** @test */
    function baseurlsegment_is_taken_into_account_for_uniqueness_check()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');

        // Use managed model with base url segment
        app(Register::class)->register(ProductManagerWithUrlAssistant::class, ProductFakeWithBaseSegments::class);
        $this->manager = app(Managers::class)->findByKey('products_with_base');


        $response = $this->createAndChangeUrlSlug('foobar-2','foobar-updated', ProductFakeWithBaseSegments::class);
        $product2 = ProductFakeWithBaseSegments::orderBy('id','DESC')->first();

        $response->assertSessionHasNoErrors('url-slugs');
        $this->assertEquals('producten/foobar-updated', $product2->url());
    }

    /**
     * @param string $from
     * @param string $to
     * @param string|null $modelClass
     * @return TestResponse
     */
    private function createAndChangeUrlSlug($from = 'foobar', $to = 'foobar-updated', string $modelClass = null): TestResponse
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => ['nl' => $from],
        ]);

        // Get the last inserted model
        $product = $modelClass ? $modelClass::orderBy('id','DESC')->first() : ProductFake::orderBy('id','DESC')->first();

        // We change it to another value
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => ['nl' => $to],
        ]);

        return $response;
    }

}
