<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Admin\Settings\Homepage;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\SettingFormParams;

class HomepageTest extends ChiefTestCase
{
    use PageFormParams;
    use SettingFormParams;

    /** @var Manager */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        app(Register::class)->model(ArticlePage::class, PageManager::class);
        $this->manager = app(Registry::class)->manager(ArticlePage::managedModelKey());
    }

    /** @test */
    public function when_setting_homepage_url_is_set_as_well()
    {
        $model = ArticlePage::create([]);

        $response = $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $model->modelReference()->getShort(),
                'en' => $model->modelReference()->getShort(),
            ],
        ]));

        $this->assertEquals($model->modelReference()->getShort(), chiefSetting('homepage'));
        $this->assertEquals($model->getMorphClass(), UrlRecord::findBySlug('/', 'nl')->model_type);
        $this->assertEquals($model->modelReference()->id(), UrlRecord::findBySlug('/', 'nl')->model_id);
    }

    /** @test */
    public function when_setting_single_homepage_all_urls_are_changed()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams());
        $model = ArticlePage::first();

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => get_class($model),
            'modelId' => $model->id,
            'links' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]);

        $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $model->modelReference()->getShort(),
                'en' => $model->modelReference()->getShort(),
            ],
        ]));

        $this->assertEquals($model->modelReference()->getShort(), chiefSetting('homepage'));

        // Assert existing nl url is redirected to homepage
        $nlHomepageUrlRecord = UrlRecord::findBySlug('/', 'nl');
        $this->assertEquals($model->getMorphClass(), $nlHomepageUrlRecord->model_type);
        $this->assertEquals($model->modelReference()->id(), $nlHomepageUrlRecord->model_id);

        $nlRedirectUrlRecord = UrlRecord::findBySlug('foobar', 'nl');
        $this->assertTrue($nlRedirectUrlRecord->isRedirect());
        $this->assertEquals($nlHomepageUrlRecord->id, $nlRedirectUrlRecord->redirect_id);

        // Assert existing en url is redirected to homepage
        $enHomepageUrlRecord = UrlRecord::findBySlug('/', 'en');
        $this->assertEquals($model->getMorphClass(), $enHomepageUrlRecord->model_type);
        $this->assertEquals($model->modelReference()->id(), $enHomepageUrlRecord->model_id);

        $enRedirectUrlRecord = UrlRecord::findBySlug('foobar', 'en');
        $this->assertTrue($enRedirectUrlRecord->isRedirect());
        $this->assertEquals($enHomepageUrlRecord->id, $enRedirectUrlRecord->redirect_id);
    }

    /** @test */
    public function when_setting_homepage_per_locale_only_those_localized_urls_of_the_model_are_changed()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams());
        $model = ArticlePage::first();

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => get_class($model),
            'modelId' => $model->id,
            'links' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]);
        $other = ArticlePage::create();

        $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $model->modelReference()->getShort(),
                'en' => $other->modelReference()->getShort(),
            ],
        ]));

        $this->assertEquals($model->modelReference()->getShort(), chiefSetting('homepage'));

        $homepageUrlRecord = UrlRecord::findBySlug('/', 'nl');
        $this->assertEquals($model->getMorphClass(), $homepageUrlRecord->model_type);
        $this->assertEquals($model->modelReference()->id(), $homepageUrlRecord->model_id);

        // Assert existing nl url is redirected to homepage
        $redirectUrlRecord = UrlRecord::findBySlug('foobar', 'nl');
        $this->assertTrue($redirectUrlRecord->isRedirect());
        $this->assertEquals($homepageUrlRecord->id, $redirectUrlRecord->redirect_id);

        $this->assertEquals($other->modelReference()->getShort(), chiefSetting('homepage', 'en'));

        // Assert existing url record is kept the same
        $this->assertEquals('foobar', UrlRecord::findByModel($model, 'en')->slug);
        $this->assertFalse(UrlRecord::findBySlug('foobar', 'en')->isRedirect());
    }

    /** @test */
    public function passing_homepage_setting_to_null_is_not_allowed()
    {
        $response = $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => [
                'nl' => 'flatreference@1',
                'en' => null,
            ],
        ]);

        $response->assertSessionHasErrors('homepage.en');
    }

    /** @test */
    public function when_setting_homepage_to_another_url_the_previous_one_is_reset_to_its_recent_redirect()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams());
        $model = ArticlePage::first();

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => get_class($model),
            'modelId' => $model->id,
            'links' => [
                'nl' => 'foobar',
                'en' => 'foobar',
            ],
        ]);

        $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $model->modelReference()->getShort(),
                'en' => $model->modelReference()->getShort(),
            ],
        ]));

        $this->assertEquals(6, UrlRecord::count());
        $this->assertEquals('/', UrlRecord::findByModel($model, 'nl')->slug);
        $this->assertEquals('/', UrlRecord::findByModel($model, 'en')->slug);
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'en')->isRedirect());

        $other = ArticlePage::create();
        $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $other->modelReference()->getShort(),
                'en' => $other->modelReference()->getShort(),
            ],
        ]));

        $this->assertEquals(6, UrlRecord::count());
        $this->assertEquals('/', UrlRecord::findByModel($other, 'nl')->slug);
        $this->assertEquals('/', UrlRecord::findByModel($other, 'en')->slug);
        $this->assertEquals('foobar', UrlRecord::findByModel($model, 'nl')->slug);
        $this->assertEquals('foobar', UrlRecord::findByModel($model, 'en')->slug);
    }

    /** @test */
    public function when_setting_a_homepage_url_the_homepage_setting_is_set_as_well()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams());
        $model = ArticlePage::first();

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => get_class($model),
            'modelId' => $model->id,
            'links' => [
                'nl' => '/',
                'en' => 'foobar',
            ],
        ]);

        $this->assertEquals($model->modelReference()->getShort(), chiefSetting('homepage', 'nl'));
        $this->assertNull(chiefSetting('homepage', 'en'));
    }

    /** @test */
    public function helper_can_check_if_page_is_homepage()
    {
        $this->asAdmin()->post($this->manager->route('store'), $this->validPageParams());
        $model = ArticlePage::first();

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => get_class($model),
            'modelId' => $model->id,
            'links' => [
                'nl' => '/',
                'en' => 'foobar',
            ],
        ]);
        $other = ArticlePage::create();

        $this->assertTrue(Homepage::is($model));
        $this->assertFalse(Homepage::is($model, 'en'));

        $this->assertFalse(Homepage::is($other));
    }
}
