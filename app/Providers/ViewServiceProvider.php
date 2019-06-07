<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        View::composer(['chief::back.managers._partials.archive-modal'], function($view){

            $targetModels = chiefMemoize('target-models', function(){
                $liveUrlRecords = UrlRecord::whereNull('redirect_id')->get();

                // Get model for each of these records...
                $models = $liveUrlRecords->map(function($urlRecord){
                    return Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);
                })->reject(function($model){
                    // Invalid references to archived or removed models where url record still exists.
                    return is_null($model);
                });

                return FlatReferencePresenter::toGroupedSelectValues($models)->toArray();
            });

            $view->with('targetModels', $targetModels);
        });
    }

    public function register()
    {
    }
}
