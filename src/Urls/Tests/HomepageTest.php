<?php

namespace Thinktomorrow\Chief\Urls\Tests;

use Thinktomorrow\Chief\Admin\Settings\Homepage;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\SettingFormParams;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class HomepageTest extends ChiefTestCase
{
    use SettingFormParams;

    public function test_when_setting_homepage_url_is_set_as_well()
    {
        $model = $this->setUpAndCreateArticle();

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

    public function test_when_setting_single_homepage_all_urls_are_changed()
    {
        $model = $this->setUpAndCreateArticle();
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'nl', 'foobar', 'online'));
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'en', 'foobaz', 'online'));

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

        $nlRedirectUrlRecord = UrlRecord::findBySlug('nl-base/foobar', 'nl');
        $this->assertTrue($nlRedirectUrlRecord->isRedirect());
        $this->assertEquals($nlHomepageUrlRecord->id, $nlRedirectUrlRecord->redirect_id);

        // Assert existing en url is redirected to homepage
        $enHomepageUrlRecord = UrlRecord::findBySlug('/', 'en');
        $this->assertEquals($model->getMorphClass(), $enHomepageUrlRecord->model_type);
        $this->assertEquals($model->modelReference()->id(), $enHomepageUrlRecord->model_id);

        $enRedirectUrlRecord = UrlRecord::findBySlug('en-base/foobaz', 'en');
        $this->assertTrue($enRedirectUrlRecord->isRedirect());
        $this->assertEquals($enHomepageUrlRecord->id, $enRedirectUrlRecord->redirect_id);
    }

    public function test_when_setting_homepage_per_locale_only_those_localized_urls_of_the_model_are_changed()
    {
        $model = $this->setUpAndCreateArticle();
        $other = ArticlePage::create();
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'nl', 'foobar', 'online'));
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'en', 'foobaz', 'online'));

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
        $redirectUrlRecord = UrlRecord::findBySlug('nl-base/foobar', 'nl');
        $this->assertTrue($redirectUrlRecord->isRedirect());
        $this->assertEquals($homepageUrlRecord->id, $redirectUrlRecord->redirect_id);

        $this->assertEquals($other->modelReference()->getShort(), chiefSetting('homepage', 'en'));

        // Assert existing url record is kept the same
        $this->assertEquals('en-base/foobaz', UrlRecord::findByModel($model, 'en')->slug);
        $this->assertFalse(UrlRecord::findBySlug('en-base/foobaz', 'en')->isRedirect());
    }

    public function test_passing_homepage_setting_to_null_is_not_allowed()
    {
        $response = $this->asAdmin()->put(route('chief.back.settings.update'), [
            'homepage' => [
                'nl' => 'flatreference@1',
                'en' => null,
            ],
        ]);

        $response->assertSessionHasErrors('homepage.en');
    }

    public function test_when_setting_homepage_to_another_url_the_previous_one_is_reset_to_its_recent_redirect()
    {
        $this->disableExceptionHandling();
        $model = $this->setUpAndCreateArticle();
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'nl', 'foobar', 'online'));
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'en', 'foobaz', 'online'));

        $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $model->modelReference()->getShort(),
                'en' => $model->modelReference()->getShort(),
            ],
        ]));

        $this->assertEquals(4, UrlRecord::count());
        $this->assertEquals('/', UrlRecord::findByModel($model, 'nl')->slug);
        $this->assertEquals('/', UrlRecord::findByModel($model, 'en')->slug);
        $this->assertTrue(UrlRecord::findBySlug('nl-base/foobar', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('en-base/foobaz', 'en')->isRedirect());

        $other = ArticlePage::create();
        $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $other->modelReference()->getShort(),
                'en' => $other->modelReference()->getShort(),
            ],
        ]));

        $this->assertEquals(4, UrlRecord::count());
        $this->assertEquals('/', UrlRecord::findByModel($other, 'nl')->slug);
        $this->assertEquals('/', UrlRecord::findByModel($other, 'en')->slug);
        $this->assertEquals('nl-base/foobar', UrlRecord::findByModel($model, 'nl')->slug);
        $this->assertEquals('en-base/foobaz', UrlRecord::findByModel($model, 'en')->slug);
    }

    public function test_helper_can_check_if_page_is_homepage()
    {
        $model = $this->setUpAndCreateArticle();
        $other = ArticlePage::create();
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'nl', 'foobar', 'online'));
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'en', 'foobaz', 'online'));

        $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams([
            'homepage' => [
                'nl' => $model->modelReference()->getShort(),
                'en' => $other->modelReference()->getShort(),
            ],
        ]));

        $this->assertTrue(Homepage::is($model));
        $this->assertFalse(Homepage::is($model, 'en'));

        $this->assertFalse(Homepage::is($other, 'nl'));
        $this->assertTrue(Homepage::is($other, 'en'));
    }
}
