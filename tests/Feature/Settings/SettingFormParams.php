<?php


namespace Thinktomorrow\Chief\Tests\Feature\Settings;

trait SettingFormParams
{
    protected function validSettingParams($overrides = [])
    {
        $params = [
            'settings' => [
                'foo' =>  'updated foo',
            ]
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdateSettingParams($overrides = [])
    {
        $params = [
            'settings' => [
                'foo' => 'updated foo',
            ]
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function assertUpdatedSettingValues($setting)
    {
        $this->assertEquals('foo', $setting->key);
        $this->assertEquals('updated foo', $setting->value);
    }
}
