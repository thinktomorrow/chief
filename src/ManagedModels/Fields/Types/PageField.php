<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferencePresenter;

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
        if (empty($whitelistedKeys)) {
            $this->grouped();
            $this->options = UrlHelper::allModelsExcept($excludedPage, $online);
            return $this;
        }

        $this->setModelsAsOptions(UrlHelper::modelsByKeys($whitelistedKeys, $excludedPage, $online));

        return $this;
    }

    public function modelReferencesAsOptions(array $modelReferences): self
    {
        $instances = [];

        foreach ($modelReferences as $modelReference) {
            if (is_string($modelReference)) {
                $modelReference = ModelReference::fromString($modelReference);
            }

            $instances[] = $modelReference->instance();
        }

        $this->setModelsAsOptions(collect($instances));

        return $this;
    }

    private function setModelsAsOptions(Collection $models): void
    {
        // options are always grouped
        $this->grouped();

        $this->options = ModelReferencePresenter::toGroupedSelectValues(collect($models))->toArray();
    }
}
