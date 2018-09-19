<?php

namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Illuminate\Support\Facades\Artisan;
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
        factory(Setting::class)->create([
            'key'   => 'foo',
            'value' => 'old foo'
        ]);

        $response = $this->asAdmin()
            ->post(route('chief.back.settings.update'), $this->validSettingParams());
        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.settings.edit'));

        $this->assertUpdatedSettingValues(Setting::first());
    }
}
