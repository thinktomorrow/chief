<?php

namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Tests\TestCase;

class SettingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief-settings.homepage', 1);
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
        $this->app['config']->set('thinktomorrow.chief-settings.homepage.value', null);

        $this->assertEquals('foobar', chiefSetting('homepage', 'foobar'));
    }

    /** @test */
    public function unknown_setting_returns_default()
    {
        $this->assertEquals('foobar', chiefSetting('xxx', 'foobar'));
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
    public function it_has_a_default_input_field_for_admin()
    {
        $setting = Setting::create([
            'key'   => 'foo',
            'value' => 'bar',
        ]);

        $this->assertInstanceOf(InputField::class, $setting->field);
        $this->assertEquals('Foo', $setting->field->label);
    }

    /** @test */
    public function it_can_have_a_custom_field_for_the_admin()
    {
        Setting::refreshFieldsFromConfig();

        $this->app['config']->set('thinktomorrow.chief.settingFields', [
            HtmlField::make('foo')->label('Foobar'),
        ]);

        $setting = Setting::create([
            'key'   => 'foo',
            'value' => 'bar',
        ]);

        $this->assertInstanceOf(HtmlField::class, $setting->field);
    }
}
