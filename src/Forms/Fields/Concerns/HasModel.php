<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    protected ?Model $model = null;
    protected array $whenModelIsSetCallbacks = [];

    public function model(Model $model): static
    {
        $this->model = $model;

        foreach ($this->whenModelIsSetCallbacks as $callback) {
            call_user_func_array($callback, [$model]);
        }

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    protected function whenModelIsSet(\Closure $callback): static
    {
        $this->whenModelIsSetCallbacks[] = $callback;

        return $this;
    }
}
