<?php


namespace Thinktomorrow\Chief\Tests\Feature\Modules;

trait ModuleFormParams
{
    protected function validModuleParams($overrides = [])
    {
        $params = [
            'collection' => 'newsletter',
            'slug' => 'new-slug',
            'trans' => [
                'nl' => [
                    'title' => 'new title',
                ],
                'en' => [
                    'title' => 'nouveau title',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdateModuleParams($overrides = [])
    {
        $params = [
            'collection' => 'newsletter',
            'slug' => 'updated-slug',
            'trans' => [
                'nl' => [
                    'title' => 'aangepaste title',
                ],
                'en' => [
                    'title' => 'updated title',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function assertNewModuleValues($module)
    {
        $this->assertEquals('new-slug', $module->slug);

        $this->assertEquals('new title', $module->{'title:nl'});
        $this->assertEquals('nouveau title', $module->{'title:en'});
    }

    protected function assertUpdatedModuleValues($module)
    {
        $this->assertEquals('updated-slug', $module->slug);

        $this->assertEquals('aangepaste title', $module->{'title:nl'});
        $this->assertEquals('updated title', $module->{'title:en'});
    }
}
