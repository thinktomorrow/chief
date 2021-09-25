<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Addons\Repeat;

use Illuminate\Support\ServiceProvider;

class RepeatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'chief-addon-repeat');
    }
}
