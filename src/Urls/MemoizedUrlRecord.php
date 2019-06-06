<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Database\Eloquent\Model;

class MemoizedUrlRecord extends UrlRecord
{
    public static function findByModel(Model $model, string $locale = null): UrlRecord
    {
        return chiefMemoize('url-records-find-by-model', function ($model, $locale = null) {
            return parent::findByModel($model, $locale);
        }, [$model, $locale]);
    }

    public static function getByModel(Model $model)
    {
        return chiefMemoize('url-records-get-by-model', function ($model) {
            return parent::getByModel($model);
        }, [$model]);
    }
}
