<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    protected null|Model|array $model = null;
    protected array $whenModelIsSetCallbacks = [];

    public function model(Model|array $model): static
    {
        $this->model = $model;

        foreach ($this->whenModelIsSetCallbacks as $callback) {
            call_user_func_array($callback, [$model, $this]);
        }

        return $this;
    }

    public function getModel(): null|Model|array
    {
        return $this->model;
    }

    protected function whenModelIsSet(\Closure $callback): static
    {
        $this->whenModelIsSetCallbacks[] = $callback;

        return $this;
    }
}
