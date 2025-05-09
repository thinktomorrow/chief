<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

trait WithMemoizedModel
{
    private static array $memoizedModels = [];

    private function getModel(): ReferableModel
    {
        if (isset(static::$memoizedModels[$this->modelReference->get()])) {
            return static::$memoizedModels[$this->modelReference->get()];
        }

        return static::$memoizedModels[$this->modelReference->get()] = $this->modelReference->instance();
    }

    private function setMemoizedModel(ReferableModel $model): void
    {
        static::$memoizedModels[$model->modelReference()->get()] = $model;
    }
}
