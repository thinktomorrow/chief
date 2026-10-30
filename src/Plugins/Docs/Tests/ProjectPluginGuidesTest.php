<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

class ProjectPluginGuidesTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            AcmeServiceProvider::class,
        ];
    }

    public function test_a_provider_outside_the_plugin_base_class_can_add_guides(): void
    {
        $this->assertSame('Archief', $this->guideTree()->find('acme::archief')->title);
    }
}
