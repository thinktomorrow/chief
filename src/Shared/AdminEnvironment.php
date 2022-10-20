<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEnvironment
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function check(Request $request): bool
    {
        if ($this->app->runningInConsole()) {
            return true;
        }

        $adminPrefix = config('chief.route.prefix', 'admin');

        return (Str::startsWith($request->path(), $adminPrefix . '/')) || $request->path() == $adminPrefix;
    }
}
