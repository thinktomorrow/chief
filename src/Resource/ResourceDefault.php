<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Fragments\Fragmentable;

trait ResourceDefault
{
    protected ?Manager $manager = null;

    public function setManager(Manager $manager): void
    {
        $this->manager = $manager;
    }

    public static function resourceKey(): string
    {
        return (new ResourceKeyFormat(static::modelClassName()))->getKey();
    }

    public function getLabel(): string
    {
        return (new ResourceKeyFormat(static::modelClassName()))->getLabel();
    }

    public function field($model, string $key): Field
    {
        $fieldModel = $model instanceof Fragmentable ? $model->fragmentModel() : $model;

        return Fields::make($this->fields($model))->find($key)->model($fieldModel);
    }

    abstract public function fields($model): iterable;
}
