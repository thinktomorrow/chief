<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductFake;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductManagerWithUrlAssistant;
use Thinktomorrow\Chief\Tests\Feature\Urls\Fakes\ProductWithBaseSegments;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;

class HomepageTest extends TestCase
{
    use PageFormParams;

    /** @var Manager */
    private $manager;

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
    function when_setting_homepage_url_is_set_as_well()
    {
        $model = ProductFake::create([]);

        $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => $model->flatReference()->get(),
        ]);

        $this->assertEquals($model->flatReference()->get(), chiefSetting('homepage'));
        $this->assertEquals($model->flatReference()->className(), UrlRecord::findBySlug('/', 'nl')->model_type);
        $this->assertEquals($model->flatReference()->id(), UrlRecord::findBySlug('/', 'nl')->model_id);
    }

    /** @test */
    function when_setting_single_homepage_all_urls_are_changed()
    {
        // Create page with url
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]));

        $model = ProductFake::first();

        $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => $model->flatReference()->get(),
        ]);

        $this->assertEquals($model->flatReference()->get(), chiefSetting('homepage'));

        // Assert existing nl url is redirected to homepage
        $nlHomepageUrlRecord = UrlRecord::findBySlug('/', 'nl');
        $this->assertEquals($model->flatReference()->className(), $nlHomepageUrlRecord->model_type);
        $this->assertEquals($model->flatReference()->id(), $nlHomepageUrlRecord->model_id);

        $nlRedirectUrlRecord = UrlRecord::findBySlug('foobar', 'nl');
        $this->assertTrue($nlRedirectUrlRecord->isRedirect());
        $this->assertEquals($nlHomepageUrlRecord->id, $nlRedirectUrlRecord->redirect_id);

        // Assert existing en url is redirected to homepage
        $enHomepageUrlRecord = UrlRecord::findBySlug('/', 'en');
        $this->assertEquals($model->flatReference()->className(), $enHomepageUrlRecord->model_type);
        $this->assertEquals($model->flatReference()->id(), $enHomepageUrlRecord->model_id);

        $enRedirectUrlRecord = UrlRecord::findBySlug('foobar', 'en');
        $this->assertTrue($enRedirectUrlRecord->isRedirect());
        $this->assertEquals($enHomepageUrlRecord->id, $enRedirectUrlRecord->redirect_id);
    }

    /** @test */
    function when_setting_homepage_per_locale_only_those_localized_urls_of_the_model_are_changed()
    {
        // Create page with url
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]));

        $model = ProductFake::first();

        $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => [
                'nl' => $model->flatReference()->get(),
            ]
        ]);

        $this->assertEquals($model->flatReference()->get(), chiefSetting('homepage'));

        $homepageUrlRecord = UrlRecord::findBySlug('/', 'nl');
        $this->assertEquals($model->flatReference()->className(), $homepageUrlRecord->model_type);
        $this->assertEquals($model->flatReference()->id(), $homepageUrlRecord->model_id);

        // Assert existing nl url is redirected to homepage
        $redirectUrlRecord = UrlRecord::findBySlug('foobar', 'nl');
        $this->assertTrue($redirectUrlRecord->isRedirect());
        $this->assertEquals($homepageUrlRecord->id, $redirectUrlRecord->redirect_id);

        $this->assertNull(chiefSetting('homepage', 'en'));

        // Assert existing url record is kept the same
        $this->assertEquals('foobar', UrlRecord::findByModel($model, 'en')->slug);
        $this->assertFalse(UrlRecord::findBySlug('foobar', 'en')->isRedirect());
    }

    /** @test */
    function passing_homepage_setting_to_null_is_not_allowed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->disableExceptionHandling();
        $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => [
                'nl' => 'flatreference@1',
                'en' => null,
            ]
        ]);
    }

    /** @test */
    function when_setting_homepage_to_another_url_the_previous_one_is_reset_to_its_recent_redirect()
    {
        // Create page with url
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]));

        $model = ProductFake::first();
        $other = ProductFake::create();

        $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => [
                'nl' => $model->flatReference()->get(),
                'en' => $model->flatReference()->get(),
            ]
        ]);

        $this->assertEquals(4, UrlRecord::count());
        $this->assertEquals('/', UrlRecord::findByModel($model,'nl')->slug);
        $this->assertEquals('/', UrlRecord::findByModel($model,'en')->slug);
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'en')->isRedirect());

        $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => [
                'nl' => $other->flatReference()->get(),
                'en' => $other->flatReference()->get(),
            ]
        ]);

        $this->assertEquals(4, UrlRecord::count());
        $this->assertEquals('/', UrlRecord::findByModel($other,'nl')->slug);
        $this->assertEquals('/', UrlRecord::findByModel($other,'en')->slug);
        $this->assertEquals('foobar', UrlRecord::findByModel($model,'nl')->slug);
        $this->assertEquals('foobar', UrlRecord::findByModel($model,'en')->slug);
    }

    /** @test */
    function when_setting_url_the_homepage_setting_is_set_as_well()
    {
        // Set default homepage
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams([
            'url-slugs' => [
                'nl' => '/',
                'en' => 'foobar',
            ],
        ]));

        $model = ProductFake::first();

        $this->assertEquals($model->flatReference()->get(), chiefSetting('homepage', 'nl'));
        $this->assertNull(chiefSetting('homepage', 'en'));
    }

}
