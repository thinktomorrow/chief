<?php

namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Settings\SettingsManager;

class UpdateSettingTest extends TestCase
{
    use SettingFormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        resolve(SettingsManager::class)->fresh();
    }

    /** @test */
    public function update_a_setting()
    {
        $this->disableExceptionHandling();

        factory(Setting::class)->create([
            'key'   => 'foo',
            'value' => 'old foo'
        ]);

        $this->asAdmin()
            ->put(route('chief.back.settings.update'), $this->validSettingParams())
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.settings.edit'));

        $this->assertUpdatedSettingValues(Setting::first());
    }
}
