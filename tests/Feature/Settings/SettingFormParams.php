<?php


namespace Thinktomorrow\Chief\Tests\Feature\Settings;

trait SettingFormParams
{
    protected function validSettingParams($overrides = [])
    {
        $params = [
            'key'   => 'new-key',
            'value' => 'new value',
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdateSettingParams($overrides = [])
    {
        $params = [
            'key'   => 'updated-key',
            'value' => 'updated value',
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function assertNewSettingValues($setting)
    {
        $this->assertEquals('new-key', $setting->key);
        $this->assertEquals('new value', $setting->value);
    }

    protected function assertUpdatedSettingValues($setting)
    {
        $this->assertEquals('updated-key', $setting->key);
        $this->assertEquals('updated value', $setting->value);
    }
}
