<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Form\SelectOptions;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;

trait HasEloquentOptionsSync
{
    public function sync(string $relation = null, string $valueKey = 'id', string $labelKey = 'title', ?callable $afterSaveCallback = null): self
    {
        if (! $relation) {
            $relation = $this->getKey();
        }

        $this->whenModelIsSet(function ($model) use ($relation, $valueKey, $labelKey, $afterSaveCallback) {
            $relationModel = $model->{$relation}()->getModel();

            $options = ($relationModel instanceof Nestable)
                ? app(SelectOptions::class)->getOptions($relationModel::class)
                : $relationModel::all()->pluck($labelKey, $valueKey)->toArray();

            $this->options($options)
                ->value($model->{$relation}->pluck($valueKey)->toArray())
                ->save(function ($model, $field, $input) use ($relation, $afterSaveCallback) {
                    $model->{$relation}()->sync($input[$relation] ?? []);
                    if ($afterSaveCallback && is_callable($afterSaveCallback)) {
                        $afterSaveCallback($model, $field, $input);
                    }
                });
        });

        return $this;
    }
}
