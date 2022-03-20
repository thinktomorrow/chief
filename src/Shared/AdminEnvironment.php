<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEnvironment
{
    private Request $request;
    private Application $app;

    public function __construct(Request $request, Application $app)
    {
        $this->request = $request;
        $this->app = $app;
    }

    public function check(): bool
    {
        if ($this->app->runningInConsole()) {
            return true;
        }

        $adminPrefix = config('chief.route.prefix', 'admin');

        return (Str::startsWith($this->request->path(), $adminPrefix . '/')) || $this->request->path() == $adminPrefix;
    }
}
