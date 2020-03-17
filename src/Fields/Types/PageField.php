<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Urls\UrlHelper;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class PageField extends SelectField
{
    private $model;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::PAGE), $key);
    }

    public function onlinePagesAsOptions(Model $excludedPage = null, array $whitelistedKeys = []): self
    {
        // options are always grouped
        $this->grouped();

        if(empty($whitelistedKeys)) {
            $this->options = UrlHelper::allModelsExcept($excludedPage);
            return $this;
        }

        $this->options = FlatReferencePresenter::toGroupedSelectValues(
            UrlHelper::modelsByKeys($whitelistedKeys)
        )->toArray();

        return $this;
    }
}
