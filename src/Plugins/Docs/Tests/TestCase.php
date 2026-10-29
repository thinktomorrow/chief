<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\DocsServiceProvider;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            DocsServiceProvider::class,
        ];
    }

    /**
     * Start each test request without the previous request's scoped instances,
     * such as the visible guide tree, as a real request would.
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $this->app->forgetScopedInstances();

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    protected function useProjectGuides(string $path): void
    {
        $this->app->singleton(DocSources::class, fn () => new DocSources($path));
    }

    protected function guideTree(): GuideTree
    {
        return $this->asAdmin()->get('/admin/docs')->assertOk()->viewData('tree');
    }

    protected function fixture(string $path): string
    {
        return __DIR__.'/fixtures/'.$path;
    }
}
