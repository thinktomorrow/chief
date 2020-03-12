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
            'key'   => 'app_name',
            'value' => 'old app_name'
        ]);

        $response = $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.settings.edit'));

        $this->assertUpdatedSettingValues(Setting::first());
    }
}
