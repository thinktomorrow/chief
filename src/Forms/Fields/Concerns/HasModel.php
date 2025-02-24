<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasModel
{
    protected ?object $model = null;

    protected array $whenModelIsSetCallbacks = [];

    public function model(object $model): static
    {
        $this->model = $model;

        foreach ($this->whenModelIsSetCallbacks as $callback) {
            call_user_func_array($callback, [$model, $this]);
        }

        return $this;
    }

    public function getModel(): ?object
    {
        return $this->model;
    }

    protected function whenModelIsSet(\Closure $callback): static
    {
        $this->whenModelIsSetCallbacks[] = $callback;

        return $this;
    }
}
