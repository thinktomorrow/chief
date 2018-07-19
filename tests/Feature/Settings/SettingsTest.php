<?php

namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Thinktomorrow\Chief\Common\TranslatableFields\FieldType;
use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Tests\TestCase;

class SettingsTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief-settings', [
            'homepage_id' => 1,
        ]);
    }

    /** @test */
    function if_setting_is_missing_in_database_it_retrieves_setting_from_config()
    {
        $this->assertEquals(1, chiefSetting('homepage_id'));
    }

    /** @test */
    function setting_from_database_overrules_the_config_one()
    {
        Setting::create(['key' => 'homepage_id', 'value' => 'foobar']);

        $this->assertEquals('foobar', chiefSetting('homepage_id'));
    }

    /** @test */
    function it_can_change_a_setting_at_runtime()
    {
        $this->assertEquals('1', chiefSetting('homepage_id'));

        chiefSetting()->set('homepage_id', 'foobar');

        $this->assertEquals('foobar', chiefSetting('homepage_id'));
    }

    /** @test */
    function if_value_is_null_the_default_is_used()
    {
        $this->app['config']->set('thinktomorrow.chief-settings', [
            'homepage_id' => null,
        ]);

        $this->assertEquals('foobar', chiefSetting('homepage_id', 'foobar'));
    }

    /** @test */
    function unknown_setting_returns_default()
    {
        $this->assertEquals('foobar', chiefSetting('xxx', 'foobar'));
    }

    /** @test */
    function it_can_store_new_settings_value()
    {
        $setting = Setting::create(['key' => 'foo', 'value' => 'bar']);

        $this->assertEquals('bar', chiefSetting('foo'));

        $setting->value = 'baz';
        $setting->save();

        $this->assertEquals('baz', chiefSetting()->fresh()->get('foo'));
    }

    /** @test */
    function it_has_information_about_the_field_presentation_for_the_admin()
    {
        $setting = Setting::create([
            'key'   => 'foo',
            'value' => 'bar',
            'field' => [
                'type'        => FieldType::HTML,
                'label'       => 'homepage',
                'description' => 'extra information',
            ],
        ]);

        $field = HtmlField::make()->label('homepage')->description('extra information');

        $this->assertEquals($field, $setting->getField());
    }
}
