<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        View::composer(['chief::back.managers._partials.archive-modal'], function ($view) {
            $targetModels = chiefMemoize('target-models', function () {
                $liveUrlRecords = DB::table('chief_urls')->select('model_type', 'model_id')->groupBy('model_type', 'model_id')->get()->mapToGroups(function($record)
                {
                    return [$record->model_type => $record->model_id];
                });

                // Get model for each of these records...
                $models = $liveUrlRecords->map(function($record, $key){
                        return Morphables::instance($key)->find($record->toArray());
                    })->each->reject(function ($model) {
                        // Invalid references to archived or removed models where url record still exists.
                        return is_null($model);
                    })->flatten();

                return FlatReferencePresenter::toGroupedSelectValues($models)->toArray();
            });

            $view->with('targetModels', $targetModels);
        });
    }

    public function register()
    {
    }
}
