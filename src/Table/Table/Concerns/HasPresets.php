<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Sortable\Sortable;
use Thinktomorrow\Chief\Table\Actions\Presets\AttachTagAction;
use Thinktomorrow\Chief\Table\Actions\Presets\DetachTagAction;
use Thinktomorrow\Chief\Table\Actions\Presets\ViewOnSiteAction;
use Thinktomorrow\Chief\Table\Columns\ColumnTag;
use Thinktomorrow\Chief\Table\Filters\Presets\TagFilter;
use Thinktomorrow\Chief\Table\Sorters\Sort;

trait HasPresets
{
    public function tagPresets(string $resourceKey): self
    {
        return $this->addQuery(function ($builder) {
            $builder->with(['tags']);
        })->filters([
            TagFilter::makeDefault($resourceKey),
        ])->columns([
            ColumnTag::make('tags')
                ->eachItem(function ($tagModel, $columnItem) {
                    $columnItem->value($tagModel->label)
                        ->color($tagModel->color);
                })
                ->label('tags'),
        ])
            ->bulkActions([
                AttachTagAction::makeDefault($resourceKey)->tertiary(),
                DetachTagAction::makeDefault($resourceKey)->tertiary(),
            ]);
    }

    public function visitablePresets(string $resourceKey): self
    {
        return $this->addQuery(function ($builder) {
            $builder->with(['urls']);
        })->rowActions([
            ViewOnSiteAction::makeDefault($resourceKey)->tertiary(),
        ]);
    }

    public function sortablePresets(string $resourceKey): self
    {
        $resource = app(Registry::class)->resource($resourceKey);
        $modelClassName = $resource::modelClassName();
        $model = new $modelClassName;

        if (! $model instanceof Sortable) {
            return $this;
        }

        return $this->sorters([
            Sort::make('manual_order')
                ->label('Volgorde volgens site')
                ->query(function ($query) use ($model) {
                    $query->orderBy($model->sortableAttribute(), 'asc');
                })->actAsDefault(),
        ]);
    }
}
