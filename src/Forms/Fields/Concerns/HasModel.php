<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    protected ?Model $model = null;

    public function model(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }
}
