<?php


namespace Thinktomorrow\Chief\Tests\Feature\Settings;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;

trait SettingFormParams
{
    protected function validSettingParams($overrides = [])
    {
        $model = ProductPageFake::create();

        $params = [
            'homepage' => ['nl' => $model->flatReference()->get(), 'en' => $model->flatReference()->get()],
            'app_name' =>  'updated app_name',
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdateSettingParams($overrides = [])
    {
        $params = [
            'app_name' => 'updated app_name',
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    protected function assertUpdatedSettingValues($setting)
    {
        $this->assertEquals('app_name', $setting->key);
        $this->assertEquals('updated app_name', $setting->value);
    }
}
