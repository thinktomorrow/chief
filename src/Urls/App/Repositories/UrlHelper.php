<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferencePresenter;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class UrlHelper
{
    /**
     * Internal api for fetching all models that have an active url.
     * This will return a grouped values array ready for select fields
     */
    public static function allOnlineModels(bool $onlySingles = false, ?Model $ignoredModel = null): array
    {
        $models = static::onlineModels($onlySingles, $ignoredModel);

        return ModelReferencePresenter::toGroupedSelectValues($models)->toArray();
    }

    /**
     * Internal api for fetching all models.
     * This will return a grouped values array ready for select fields
     */
    public static function allModelsExcept(?Model $ignoredModel = null, bool $online = false): array
    {
        $models = static::models(false, $ignoredModel, $online);

        return ModelReferencePresenter::toGroupedSelectValues($models)->toArray();
    }

    public static function allOnlineSingles(): array
    {
        return static::allOnlineModels(true);
    }

    /**
     * Fetch all models that have an active url. Here we check on the ignored model
     * after retrieval from database so our memoized cache gets optimal usage.
     */
    public static function onlineModels(bool $onlySingles = false, ?Model $ignoredModel = null): Collection
    {
        return self::models($onlySingles, $ignoredModel, true);
    }

    private static function models(bool $onlySingles = false, ?Model $ignoredModel = null, bool $online = true)
    {
        $types = [];

        if ($onlySingles) {
            $types = ['singles', 'single', 'page'];
        }

        return self::modelsByType($types, $ignoredModel, $online);
    }

    public static function modelsByType(array $types, ?Model $ignoredModel = null, bool $online = true)
    {
        $models = chiefMemoize('all-online-models-'.implode('_', $types), function () use ($types, $online) {
            $builder = UrlRecord::whereNull('redirect_id')
                ->select('model_type', 'model_id')
                ->groupBy('model_type', 'model_id');

            if (! empty($types)) {
                $builder->whereIn('model_type', $types);
            }

            return $builder->get()->mapToGroups(function ($record) {
                return [$record->model_type => $record->model_id];
            })->map(function ($record, $key) {
                // TODO: change to ModelReference instead
                return Morphables::instance($key)->find($record->toArray());
            })->map->reject(function ($model) use ($online) {
                if ($online) {
                    return is_null($model) || (public_method_exists($model, 'isPublished') && ! $model->isPublished());
                } // Invalid references to archived or removed models where url record still exists.

                return is_null($model);
            })->flatten();
        });

        if ($ignoredModel) {
            $models = $models->reject(function ($model) use ($ignoredModel) {
                return $model::class === get_class($ignoredModel) && $model->id === $ignoredModel->id;
            });
        }

        return $models;
    }
}
