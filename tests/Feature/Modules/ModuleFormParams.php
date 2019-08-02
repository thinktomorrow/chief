<?php


namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;

trait ModuleFormParams
{
    protected function validModuleParams($overrides = [])
    {
        $params = [
            'morph_key' => NewsletterModuleFake::class,
            'internal_title' => 'new-slug',
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdateModuleParams($overrides = [])
    {
        $params = [
            'morph_key' => NewsletterModuleFake::class,
            'internal_title' => 'updated-slug',
            'trans' => [
                'nl' => [
                    'content' => 'aangepaste content',
                ],
                'en' => [
                    'content' => 'updated content',
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
        $this->assertEquals(NewsletterModuleFake::class, $module->morph_key);
        $this->assertEquals('new-slug', $module->internal_title);
    }

    protected function assertUpdatedModuleValues($module)
    {
        $this->assertEquals('updated-slug', $module->internal_title);

        $this->assertEquals('aangepaste content', $module->{'content:nl'});
        $this->assertEquals('updated content', $module->{'content:en'});
    }
}
