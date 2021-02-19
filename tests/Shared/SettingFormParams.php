<?php


namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

trait SettingFormParams
{
    protected function validSettingParams($overrides = [])
    {
        $model = ArticlePage::create();

        $params = [
            'homepage' => ['nl' => $model->modelReference()->get(), 'en' => $model->modelReference()->get()],
            'app_name' => 'updated app_name',
            'contact_email' => 'valid@mail.com',
            'contact_name' => 'foobar',
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
