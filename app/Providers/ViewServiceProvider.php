<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Urls\UrlHelper;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        View::composer(['chief::back.managers._partials.archive-modal'], function ($view) {
            $viewData = $view->getData();

            $ignoredModel = (isset($viewData['manager']))
                ? $viewData['manager']->model()
                : null;

            $view->with('targetModels', UrlHelper::allOnlineModels(false, $ignoredModel));
        });
    }

    public function register()
    {
        //
    }
}
