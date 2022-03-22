<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasOptions;

class Select extends Component implements Field
{
    use HasMultiple;
    use HasOptions;

    protected string $view = 'chief-form::fields.select';
    protected string $windowView = 'chief-form::fields.select-window';

    public function sync(string $relation = null, string $valueKey = 'id', string $labelKey = 'title'): self
    {
        if (! $relation) {
            $relation = $this->getKey();
        }

        $this->whenModelIsSet(function ($model) use ($relation, $valueKey, $labelKey) {
            $relationModel = $model->{$relation}()->getModel();

            $this->options($relationModel::all()->pluck($labelKey, $valueKey)->toArray())
                ->value($model->{$relation}->pluck($valueKey)->toArray())
                ->save(function ($model, $field, $input) use ($relation) {
                    $model->{$relation}()->sync($input[$relation] ?? []);
                });
        });

        return $this;
    }
}
