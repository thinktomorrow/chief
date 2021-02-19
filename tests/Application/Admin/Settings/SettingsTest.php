<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Settings;

use Thinktomorrow\Chief\Admin\Settings\Setting;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\HtmlField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class SettingsTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('chief-settings.homepage', 1);
    }

    /** @test */
    public function if_setting_is_missing_in_database_it_retrieves_setting_from_config()
    {
        $this->assertEquals(1, chiefSetting('homepage'));
    }

    /** @test */
    public function setting_from_database_overrules_the_config_one()
    {
        Setting::create(['key' => 'homepage', 'value' => 'foobar']);

        $this->assertEquals('foobar', chiefSetting('homepage'));
    }

    /** @test */
    public function it_can_change_a_setting_at_runtime()
    {
        $this->assertEquals('1', chiefSetting('homepage'));

        chiefSetting()->set('homepage', 'foobar');

        $this->assertEquals('foobar', chiefSetting('homepage'));
    }

    /** @test */
    public function if_value_is_null_the_default_is_used()
    {
        $this->app['config']->set('chief-settings.homepage', null);

        $this->assertEquals('foobar', chiefSetting('homepage', null, 'foobar'));
    }

    /** @test */
    public function unknown_setting_returns_default()
    {
        $this->assertEquals('foobar', chiefSetting('xxx', null, 'foobar'));
    }

    /** @test */
    public function it_can_store_new_settings_value()
    {
        $setting = Setting::create(['key' => 'foo', 'value' => 'bar']);

        $this->assertEquals('bar', chiefSetting('foo'));

        $setting->value = 'baz';
        $setting->save();

        $this->assertEquals('baz', chiefSetting()->fresh()->get('foo'));
    }

    /** @test */
    public function it_can_store_new_translatable_settings_value()
    {
        Setting::create(['key' => 'foo', 'value' => [
            'nl' => 'nl value',
            'en' => 'en value',
        ]]);

        $this->assertEquals('nl value', chiefSetting('foo'));
        $this->assertEquals('en value', chiefSetting('foo', 'en'));
    }

    /** @test */
    public function it_has_a_default_input_field_for_admin()
    {
        $setting = Setting::create([
            'key' => 'foo',
            'value' => 'bar',
        ]);

        $this->assertInstanceOf(InputField::class, $setting->field);
        $this->assertEquals('Foo', $setting->field->getLabel());
    }

    /** @test */
    public function it_can_have_a_custom_field_for_the_admin()
    {
        Setting::refreshFieldsFromConfig();

        $this->app['config']->set('chief.settingFields', [
            HtmlField::make('foo')->label('Foobar'),
        ]);

        $setting = Setting::create([
            'key' => 'foo',
            'value' => 'bar',
        ]);

        $this->assertInstanceOf(HtmlField::class, $setting->field);
    }
}
