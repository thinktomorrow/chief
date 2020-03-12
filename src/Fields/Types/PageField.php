<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Urls\UrlHelper;
use Thinktomorrow\Chief\Fields\Types\SelectField;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class PageField extends SelectField
{
    private $model;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::PAGE), $key);
    }

    public function options(array $morphKeys = [])
    {
        if (! empty($morphKeys)) {
            $morphKeys = collect($morphKeys)->map(function ($key) {
                return (new $key())->getMorphClass();
            });

            $pages = UrlHelper::modelsByType($morphKeys->toArray())->get();
            $pages = FlatReferencePresenter::toGroupedSelectValues($pages)->toArray();
        } else {
            $pages = UrlHelper::allModelsWithoutSelf($this->model);
        }

        $this->options = $pages;

        return $this->grouped();
    }

    public function exclude(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}
