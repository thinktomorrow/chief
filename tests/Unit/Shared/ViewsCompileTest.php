<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared;

use Illuminate\Support\Facades\File;
use Thinktomorrow\Chief\Tests\TestCase;

class ViewsCompileTest extends TestCase
{
    /**
     * Every core view must compile without optional plugins, so php artisan view:cache works on a default install.
     * Plugin views are left out because they are only registered when the plugin is enabled.
     */
    public function test_all_core_views_compile_without_plugins(): void
    {
        $root = realpath(__DIR__.'/../../..');

        $views = collect(File::allFiles($root.'/resources/views'))
            ->merge(File::allFiles($root.'/src'))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.blade.php'))
            ->reject(fn ($file) => str_contains($file->getPathname(), '/Tests/') || str_contains($file->getPathname(), '/src/Plugins/'));

        $this->assertNotEmpty($views);

        foreach ($views as $view) {
            $compiled = app('blade.compiler')->compileString($view->getContents());

            $this->assertIsString($compiled, $view->getPathname());
        }
    }
}
