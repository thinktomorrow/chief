<?php

namespace Thinktomorrow\Chief\Shared\ModelReferences;

trait ReferableModelDefault
{
    public function modelReference(): ModelReference
    {
        return ModelReference::make(static::class, $this->id);
    }

    public function modelReferenceLabel(): string
    {
        return $this->title ?? class_basename($this);
    }

    public function modelReferenceGroup(): string
    {
        return class_basename($this);
    }
}
