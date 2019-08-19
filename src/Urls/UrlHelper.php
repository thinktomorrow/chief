<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class UrlHelper
{
    /**
     * Internal api for fetching all models that have an active url
     *
     * @param bool $onlySingles
     * @param Model|null $ignoredModel
     * @return array
     */
    public static function allOnlineModels(bool $onlySingles = false, Model $ignoredModel = null): array
    {
        return chiefMemoize('all-online-models', function () use ($onlySingles, $ignoredModel) {
            $builder = UrlRecord::whereNull('redirect_id')
                ->select('model_type', 'model_id')
                ->groupBy('model_type', 'model_id');

            if ($onlySingles) {
                $builder->where('model_type', 'singles');
            }

            if ($ignoredModel) {
                $builder->whereNotIn('id', function ($query) use ($ignoredModel) {
                    $query->select('id')
                        ->from('chief_urls')
                        ->where('model_type', '=', $ignoredModel->getMorphClass())
                        ->where('model_id', '=', $ignoredModel->id);
                });
            }

            $liveUrlRecords = $builder->get()->mapToGroups(function ($record) {
                return [$record->model_type => $record->model_id];
            });

            // Get model for each of these records...
            $models = $liveUrlRecords->map(function ($record, $key) {
                return Morphables::instance($key)->find($record->toArray());
            })->map->reject(function ($model) {
                // Invalid references to archived or removed models where url record still exists.
                return is_null($model) || !$model->isPublished();
            })->flatten();

            return FlatReferencePresenter::toGroupedSelectValues($models)->toArray();
        }, [$onlySingles, $ignoredModel]);
    }

    public static function allOnlineSingles()
    {
        return static::allOnlineModels(true);
    }
}
