<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Models;

trait ForwardFragmentProperties
{
    /**
     * Forward property requests to underlying fragmentModel.
     *
     * This trait is not meant to be used by an Eloquent model.
     * Note that this conflicts with any existing magic methods
     * such as the ones Eloquent models provide.
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (! $this->hasFragmentModel()) {
            return null;
        }

        return $this->getFragmentModel()->$key;
    }
}
