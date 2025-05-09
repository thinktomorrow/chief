<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Thinktomorrow\Chief\Forms\Concerns\HasComponents;

trait HasModel
{
    protected ?object $model = null;

    protected array $whenModelIsSetCallbacks = [];

    /**
     * Recursive method to set the model on all components.
     */
    public function model(object $model): static
    {
        $this->model = $model;

        foreach ($this->whenModelIsSetCallbacks as $callback) {
            call_user_func_array($callback, [$model, $this]);
        }

        if ($this instanceof HasComponents) {
            foreach ($this->getComponents() as $component) {
                $component->model($model);
            }
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
