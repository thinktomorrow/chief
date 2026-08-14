<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Squanto;

use Thinktomorrow\Squanto\Squanto;

trait InteractsWithSquantoSources
{
    private function registerPluginSource(): void
    {
        Squanto::registerPlugin(dirname((string) config('squanto.lang_path')).'/plugin/lang', 'chief-form-plugin', 'Form plugin');
    }

    private function skipWithoutNamespacedSquantoSupport(): void
    {
        if (! class_exists(Squanto::class) || ! method_exists(Squanto::class, 'registerPlugin')) {
            $this->markTestSkipped('Requires Squanto source registry support.');
        }
    }
}
