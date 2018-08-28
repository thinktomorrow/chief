<?php

namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Tests\TestCase;

class CreateSettingTest extends TestCase
{
    use SettingFormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function creating_a_new_setting()
    {
        $response = $this->asAdmin()
            ->post(route('chief.back.settings.store'), $this->validSettingParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.settings.edit'));

        $this->assertCount(1, Setting::all());
        $this->assertNewSettingValues(Setting::first());
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_setting()
    {
        $response = $this->post(route('chief.back.settings.store'), $this->validSettingParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, Setting::all());
    }

    /** @test */
    public function when_creating_setting_title_is_required()
    {
        $this->assertValidation(new Setting(), 'trans.nl.title', $this->validSettingParams(['trans.nl.title' => '']),
            route('chief.back.settings.index'),
            route('chief.back.settings.store')
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $setting = factory(Setting::class)->create([
            'title:nl'  => 'titel nl',
            'slug:nl'   => 'foobarnl'
        ]);

        $this->assertCount(1, Setting::all());

        $response = $this->asAdmin()
            ->post(route('chief.back.settings.store'), $this->validSettingParams([
                'trans.nl.title'  => 'foobarnl',
                'trans.en.title'  => 'foobaren',
            ])
            );

        $response->assertStatus(302);

        $settings = Setting::all();
        $this->assertCount(2, $settings);
        $this->assertNotEquals($settings->first()->slug, $settings->last()->slug);
    }

    /** @test */
    public function slug_must_be_unique_even_with_translations()
    {
        $setting = factory(Setting::class)->create([
            'title:nl'  => 'titel nl',
            'slug:nl'   => 'foobar'
        ]);

        $this->assertCount(1, Setting::all());

        $response = $this->asAdmin()
            ->post(route('chief.back.settings.store'), $this->validSettingParams([
                'trans.nl.slug'  => 'foobar',
                'trans.en.slug'  => 'foobar',
            ])
            );
        $response->assertStatus(302);

        $settings = Setting::all();
        $this->assertCount(2, $settings);
        $this->assertNotEquals($settings->first()->slug, $settings->last()->slug);
    }

    /** @test */
    public function uses_title_as_slug_if_slug_is_empty()
    {
        $response = $this->asAdmin()
            ->post(route('chief.back.settings.store'), $this->validSettingParams([
                'trans.nl.title'    => 'foobar',
                'trans.nl.slug'     => '',
                'trans.en.title'    => 'foobar',
                'trans.en.slug'     => '',
            ])
            );
        $response->assertStatus(302);

        $settings = Setting::all();
        $this->assertCount(1, $settings);
        $this->assertNotNull($settings->first()->slug);
    }
}
