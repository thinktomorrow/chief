<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

trait ForwardFragmentProperties
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
        return $this->fragmentModel()->$key;
    }
}
