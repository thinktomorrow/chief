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
        View::composer(['chief::back.managers._modals.archive-modal'], function ($view) {
            $viewData = $view->getData();

            $ignoredModel = (isset($viewData['manager']))
                ? $viewData['manager']->existingModel()
                : null;

            $onlineModels = UrlHelper::allOnlineModels(false, $ignoredModel);

            $view->with('targetModels', $onlineModels);
        });
    }

    public function register()
    {
        //
    }
}
