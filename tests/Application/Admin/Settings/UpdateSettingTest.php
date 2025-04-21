<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Settings;

use Thinktomorrow\Chief\Admin\Settings\Setting;
use Thinktomorrow\Chief\Admin\Settings\Settings;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\SettingFormParams;

class UpdateSettingTest extends ChiefTestCase
{
    use SettingFormParams;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        resolve(Settings::class)->fresh();
    }

    public function test_update_a_setting()
    {
        $this->disableExceptionHandling();
        Setting::create([
            'key' => 'app_name',
            'value' => 'old app_name',
        ]);

        $response = $this->asAdmin()->put(route('chief.back.settings.update'), $this->validSettingParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.settings.edit'));

        $this->assertUpdatedSettingValues(Setting::first());
    }
}
