<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
//        Builder::macro('ignoreCollection', function(){
//            return $this;
//        });
    }

    public function register()
    {
    }
}
