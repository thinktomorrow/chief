<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class UrlHelper
{
    /**
     * Internal api for fetching all models that have an active url
     *
     * @param bool $onlySingles
     * @return array
     */
    public static function allOnlineModels(bool $onlySingles = false): array
    {
        return chiefMemoize('all-online-models', function () use ($onlySingles) {
            $builder = UrlRecord::whereNull('redirect_id')->select('model_type', 'model_id')->groupBy('model_type', 'model_id');

            if ($onlySingles) {
                $builder->where('model_type', 'singles');
            }

            $liveUrlRecords = $builder->get()->mapToGroups(function ($record) {
                return [$record->model_type => $record->model_id];
            });

            // Get model for each of these records...
            $models = $liveUrlRecords->map(function ($record, $key) {
                return Morphables::instance($key)->find($record->toArray());
            })->each->reject(function ($model) {
                // Invalid references to archived or removed models where url record still exists.
                return is_null($model);
            })->flatten();

            return FlatReferencePresenter::toGroupedSelectValues($models)->toArray();
        });
    }

    public static function allOnlineSingles()
    {
        return static::allOnlineModels(true);
    }
}
