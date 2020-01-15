<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Database\Eloquent\Model;

class MemoizedUrlRecord extends UrlRecord
{
    public static $cachedRecords;

    public static function clearCachedRecords()
    {
        static::$cachedRecords = null;
    }

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
        if (!static::$cachedRecords) {
            static::$cachedRecords = parent::all();
        }

        $record = static::$cachedRecords
            ->where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->where('locale', $locale)
            ->sortBy('redirect_id')
            ->first();

        if (!$record) {
            throw new UrlRecordNotFound('No url record found for model ['.$model->getMorphClass().'@'.$model->id.'] for locale ['.$locale.'].');
        }

        return $record;
    }

    public static function getByModel(Model $model)
    {
        return chiefMemoize('url-records-get-by-model', function ($model) {
            return parent::getByModel($model);
        }, [$model]);
    }
}
