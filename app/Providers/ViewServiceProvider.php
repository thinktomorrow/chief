<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        View::composer(['chief::back.managers._partials.archive-modal'], function ($view) {
            $view->with('targetModels', UrlRecord::allOnlineModels());
        });
    }

    public function register()
    {
        //
    }
}
