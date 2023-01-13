<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

trait ForwardNestableModelCalls
{
    /**
     * Forward property requests to underlying fragmentModel.
     *
     * This trait is not meant to be used by an Eloquent model.
     * Note that this conflicts with any existing magic methods
     * such as the ones Eloquent models provide.
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getModel()->$key;
    }

    public function __call($key, $parameters)
    {
        return $this->getModel()->$key(...$parameters);
    }
}
