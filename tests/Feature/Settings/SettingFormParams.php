<?php


namespace Thinktomorrow\Chief\Tests\Feature\Settings;

trait SettingFormParams
{
    protected function validSettingParams($overrides = [])
    {
        $params = [
            'homepage' =>  'updated homepage',
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdateSettingParams($overrides = [])
    {
        $params = [
            'homepage' => 'updated homepage',
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function assertUpdatedSettingValues($setting)
    {
        $this->assertEquals('homepage', $setting->key);
        $this->assertEquals('updated homepage', $setting->value);
    }
}
