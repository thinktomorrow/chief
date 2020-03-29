<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Urls\UrlHelper;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class PageField extends SelectField
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::PAGE), $key);
    }

    /**
     * Provide visitable pages as options, online pages only.
     *
     * @param Model|null $excludedPage
     * @param array $whitelistedKeys
     * @return $this
     */
    public function onlinePagesAsOptions(Model $excludedPage = null, array $whitelistedKeys = []): self
    {
        return $this->pagesAsOptions($excludedPage, $whitelistedKeys, true);
    }

    /**
     * Provide visitable pages as options, both on- and offline ones.
     * @param Model|null $excludedPage
     * @param array $whitelistedKeys
     * @param bool $online
     * @return $this
     */
    public function pagesAsOptions(Model $excludedPage = null, array $whitelistedKeys = [], $online = false): self
    {
        // options are always grouped
        $this->grouped();

        if (empty($whitelistedKeys)) {
            $this->options = UrlHelper::allModelsExcept($excludedPage, $online);
            return $this;
        }

        $this->options = FlatReferencePresenter::toGroupedSelectValues(
            UrlHelper::modelsByKeys($whitelistedKeys, $excludedPage, $online)
        )->toArray();

        return $this;
    }
}
