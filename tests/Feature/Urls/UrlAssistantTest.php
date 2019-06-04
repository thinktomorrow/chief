<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Foundation\Testing\TestResponse;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductFake;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductManagerWithUrlAssistant;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
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

        app(Register::class)->register('products', ProductManagerWithUrlAssistant::class, ProductFake::class);

        $this->manager = app(Managers::class)->findByKey('products');
    }

    /** @test */
    function it_adds_an_url_on_creation()
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
    function it_adds_a_wildcard_slug_for_all_available_locales()
    {
        // There are 2 locales: nl en en for this model
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [UrlAssistant::WILDCARD => 'foobar'],
        ]);

        $urlRecordNl = UrlRecord::findBySlug('foobar', 'nl');
        $urlRecordEn = UrlRecord::findBySlug('foobar', 'en');

        $this->assertEquals(2, UrlRecord::count());
        $this->assertEquals('nl', $urlRecordNl->locale);
        $this->assertEquals('en', $urlRecordEn->locale);
        $this->assertTrue($urlRecordNl->isManagedAsWildCard());
        $this->assertTrue($urlRecordEn->isManagedAsWildCard());
    }

    /** @test */
    function it_adds_a_wildcard_slug_prepended_with_the_basesegment()
    {
        app(Register::class)->register('products', PageManager::class, ProductWithBaseSegments::class);
        $manager = app(Managers::class)->findByKey('products');

        $this->asAdmin()->post($manager->route('store'), $this->validPageParams([
            'url-slugs' => [UrlAssistant::WILDCARD => 'foobar'],
        ]));

        $urlRecordEn = UrlRecord::findBySlug('products/foobar', 'en');
        $urlRecordNl = UrlRecord::findBySlug('producten/foobar', 'nl');

        $this->assertEquals(2, UrlRecord::count());
        $this->assertEquals('nl', $urlRecordNl->locale);
        $this->assertEquals('en', $urlRecordEn->locale);
        $this->assertTrue($urlRecordNl->isManagedAsWildCard());
        $this->assertTrue($urlRecordEn->isManagedAsWildCard());
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
    function updating_to_same_url_as_a_redirect_of_different_model_will_fail_validation()
    {
        $this->createAndChangeUrlSlug('foobar','foobar-updated');
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        $response = $this->createAndChangeUrlSlug('other','foobar');

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertCount(3,UrlRecord::all()); // And not 4 - so we know the new one is not added

        // verify url still points to old model
        $this->assertEquals(1, UrlRecord::findBySlug('foobar', 'nl')->model_id);
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('other', 'nl')->isRedirect());
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
    function updating_wildcard_to_empty_string_removes_all_wildcards()
    {
        config()->set('translatable.locales',['nl','fr']);
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [
                UrlAssistant::WILDCARD => 'foobar-wildcard',
                'nl' => 'foobar-nl'
            ],
        ]);
        $this->assertCount(2,UrlRecord::all());

        $product = ProductFake::orderBy('id','DESC')->first();
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => [
                UrlAssistant::WILDCARD => '',
                'nl' => 'foobar-nl'
            ],
        ]);

        $this->assertCount(1,UrlRecord::all());
        $this->assertFalse(UrlRecord::findBySlug('foobar-nl', 'nl')->isManagedAsWildCard());
    }

    /** @test */
    function updating_wildcard_to_empty_string_removes_wildcards()
    {
        config()->set('translatable.locales',['nl','fr']);
        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [
                UrlAssistant::WILDCARD => 'foobar-wildcard',
            ],
        ]);
        $this->assertCount(2,UrlRecord::all());

        $product = ProductFake::orderBy('id','DESC')->first();
        $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => [
                UrlAssistant::WILDCARD => '',
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
    function specific_locale_slug_trumps_wildcard_slug()
    {
        config()->set('translatable.locales',['nl','fr']);

        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [
                UrlAssistant::WILDCARD => 'foobar-wildcard',
                'nl' => 'foobar-nl',
                'fr' => '',
            ],
        ]);

        $this->assertCount(2,UrlRecord::all());

        $this->assertFalse(UrlRecord::findBySlug('foobar-nl', 'nl')->isManagedAsWildCard());
        $this->assertTrue(UrlRecord::findBySlug('foobar-wildcard', 'fr')->isManagedAsWildCard());
    }

    /** @test */
    function specific_locale_slug_trumps_wildcard_slug_on_update()
    {
        config()->set('translatable.locales',['nl','fr']);

        $this->asAdmin()->post($this->manager->route('store'), [
            'url-slugs' => [
                UrlAssistant::WILDCARD => 'foobar-wildcard',
                'nl' => '',
                'fr' => '',
            ],
        ]);

        $product = ProductFake::orderBy('id','DESC')->first();
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => [
                UrlAssistant::WILDCARD => 'foobar-wildcard',
                'nl' => 'foobar-nl',
                'fr' => '',
            ],
        ]);

        $this->assertCount(3,UrlRecord::all());
        $this->assertFalse(UrlRecord::findBySlug('foobar-nl', 'nl')->isManagedAsWildCard());
        $this->assertTrue(UrlRecord::findBySlug('foobar-wildcard', 'fr')->isManagedAsWildCard());
        $this->assertTrue(UrlRecord::findBySlug('foobar-wildcard', 'nl')->isManagedAsWildCard());
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
        $product = ProductFake::orderBy('id','DESC')->first();

        // We change it to another value
        $response = $this->asAdmin()->put($this->manager->manage($product)->route('update'), [
            'url-slugs' => ['nl' => $to],
        ]);

        return $response;
    }

}
