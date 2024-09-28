<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\SelectOptions;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;

trait HasEloquentOptionsSync
{
    public function sync(string $relation = null, string $valueKey = 'id', string $labelKey = 'title', ?callable $afterSaveCallback = null, bool $syncInOrder = false, string $pivotOrderColumn = 'sort'): self
    {
        if (! $relation) {
            $relation = $this->getKey();
        }

        $this->whenModelIsSet(function ($model) use ($relation, $valueKey, $labelKey, $afterSaveCallback, $syncInOrder, $pivotOrderColumn) {
            $relationModel = $model->{$relation}()->getModel();

            $options = ($relationModel instanceof Nestable)
                ? app(SelectOptions::class)->getOptions($relationModel::class)
                : $relationModel::all()->pluck($labelKey, $valueKey)->toArray();

            $this->options($options)
                ->value($model->{$relation}->pluck($valueKey)->toArray())
                ->save(function ($model, $field, $input) use ($relation, $afterSaveCallback, $syncInOrder, $pivotOrderColumn) {

                    $syncData = $input[$relation] ?? [];

                    if ($syncInOrder && isset($input[$relation])) {
                        foreach ($input[$relation] as $i => $id) {
                            $syncData[$id] = [$pivotOrderColumn => $i];
                        }
                    }

                    $model->{$relation}()->sync($syncData);

                    if ($afterSaveCallback && is_callable($afterSaveCallback)) {
                        $afterSaveCallback($model, $field, $input);
                    }
                });
        });

        return $this;
    }

    /**
     * Sync the relations in given order.
     */
    public function syncInOrder(string $relation = null, string $valueKey = 'id', string $labelKey = 'title', ?callable $afterSaveCallback = null): self
    {
        return $this->sync($relation, $valueKey, $labelKey, $afterSaveCallback, true, 'sort');
    }
}
