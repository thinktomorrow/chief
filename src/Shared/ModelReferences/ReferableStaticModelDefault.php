<?php

namespace Thinktomorrow\Chief\Shared\ModelReferences;

trait ReferableStaticModelDefault
{
    public function modelReference(): ModelReference
    {
        return ModelReference::fromStatic(static::class);
    }

    public function modelReferenceLabel(): string
    {
        return class_basename($this);
    }

    public function modelReferenceGroup(): string
    {
        return class_basename($this);
    }
}
