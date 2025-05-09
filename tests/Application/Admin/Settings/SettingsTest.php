<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Settings;

use Thinktomorrow\Chief\Admin\Settings\Setting;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class SettingsTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('chief-settings.homepage', 1);
    }

    public function test_if_setting_is_missing_in_database_it_retrieves_setting_from_config()
    {
        $this->assertEquals(1, chiefSetting('homepage'));
    }

    public function test_setting_from_database_overrules_the_config_one()
    {
        Setting::create(['key' => 'homepage', 'value' => 'foobar']);

        $this->assertEquals('foobar', chiefSetting('homepage'));
    }

    public function test_it_can_change_a_setting_at_runtime()
    {
        $this->assertEquals('1', chiefSetting('homepage'));

        chiefSetting()->set('homepage', 'foobar');

        $this->assertEquals('foobar', chiefSetting('homepage'));
    }

    public function test_if_value_is_null_the_default_is_used()
    {
        $this->app['config']->set('chief-settings.homepage', null);

        $this->assertEquals('foobar', chiefSetting('homepage', null, 'foobar'));
    }

    public function test_unknown_setting_returns_default()
    {
        $this->assertEquals('foobar', chiefSetting('xxx', null, 'foobar'));
    }

    public function test_it_can_store_new_settings_value()
    {
        $setting = Setting::create(['key' => 'foo', 'value' => 'bar']);

        $this->assertEquals('bar', chiefSetting('foo'));

        $setting->value = 'baz';
        $setting->save();

        $this->assertEquals('baz', chiefSetting()->fresh()->get('foo'));
    }

    public function test_it_can_store_new_translatable_settings_value()
    {
        Setting::create(['key' => 'foo', 'value' => [
            'nl' => 'nl value',
            'en' => 'en value',
        ]]);

        $this->assertEquals('nl value', chiefSetting('foo'));
        $this->assertEquals('en value', chiefSetting('foo', 'en'));
    }
}
