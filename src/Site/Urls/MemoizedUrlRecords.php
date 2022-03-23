<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MemoizedUrlRecords
{
    /**
     * Here we cache all the url records and determine the proper url record
     * via the collection methods. This is a lot faster on large data sets.
     *
     * @param Model $model
     * @param string|null $locale
     * @return UrlRecord
     * @throws UrlRecordNotFound
     */
    public static function findByModel(Model $model, string $locale = null): UrlRecord
    {
        $record = static::getByModel($model)
            ->where('locale', $locale)
            ->sortBy('redirect_id')
            ->first();

        if (! $record) {
            throw new UrlRecordNotFound('No url record found for model [' . $model->getMorphClass() . '@' . $model->id . '] for locale [' . $locale . '].');
        }

        return $record;
    }

    public static function getByModel(Model $model): Collection
    {
        return UrlRecord::where('model_type', $model->getMorphClass())
                         ->where('model_id', $model->id)
                         ->get();
    }
}
