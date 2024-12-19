<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Actions\Presets\AttachTagAction;
use Thinktomorrow\Chief\Table\Actions\Presets\DetachTagAction;
use Thinktomorrow\Chief\Table\Columns\ColumnTag;
use Thinktomorrow\Chief\Table\Filters\Presets\TagFilter;

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
                ->items(function ($model) {
                    return $model->tags;
                })
                ->eachItem(function ($columnItem, $tagModel) {
                    $columnItem->value($tagModel->label)
                               ->color($tagModel->color);
                })
                ->label('tags'),
        ])
            ->bulkActions([
                AttachTagAction::makeDefault($resourceKey),
                DetachTagAction::makeDefault($resourceKey),
            ]);
    }
}
