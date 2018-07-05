<?php

namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;

class SettingsTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    function unknown_setting_returns_null()
    {
        $this->assertNull(setting('unknown'));
    }
    
    /** @test */
    function it_can_retrieve_a_setting()
    {
        setting()->set('foo', 'bar');

        $this->assertEquals('bar', setting('foo'));
    }

    /** @test */
    function it_can_retrieve_a_setting_from_config()
    {
        setting()->set('foo', 'bar');

        $this->assertEquals('bar', setting('foo'));
    }

    /** @test */
    function a_setting_from_database_has_priority()
    {
        $setting = Setting::create(['key' => 'foo', 'value' => 'bar']);

        $this->assertEquals('bar', setting('foo'));

        $setting->value = 'baz';
        $setting->save();

        $this->assertEquals('baz', setting()->fresh()->get('foo'));
    }
   
}
