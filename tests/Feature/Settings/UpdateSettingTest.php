<?php

namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Settings\Settings;

class UpdateSettingTest extends TestCase
{
    use SettingFormParams;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        resolve(Settings::class)->fresh();
    }

    /** @test */
    public function update_a_setting()
    {
        $this->disableExceptionHandling();

        Setting::create([
            'key'   => 'homepage',
            'value' => 'old homepage'
        ]);

        $this->asAdmin()
            ->put(route('chief.back.settings.update'), $this->validSettingParams())
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.settings.edit'));

        $this->assertUpdatedSettingValues(Setting::first());
    }
}
