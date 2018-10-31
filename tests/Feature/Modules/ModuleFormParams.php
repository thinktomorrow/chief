<?php


namespace Thinktomorrow\Chief\Tests\Feature\Modules;

trait ModuleFormParams
{
    protected function validModuleParams($overrides = [])
    {
        $params = [
            'morph_key' => 'newsletter',
            'slug' => 'new-slug',
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdateModuleParams($overrides = [])
    {
        $params = [
            'morph_key' => 'newsletter',
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
        $this->assertEquals('newsletter', $module->collection);
        $this->assertEquals('new-slug', $module->slug);
    }

    protected function assertUpdatedModuleValues($module)
    {
        $this->assertEquals('updated-slug', $module->slug);

        $this->assertEquals('aangepaste title', $module->{'title:nl'});
        $this->assertEquals('updated title', $module->{'title:en'});
    }
}
