<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Resource;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Managers\Manager;

trait ResourceDefault
{
    protected ?Manager $manager = null;

    public static function modelClassName(): string
    {
        return static::class;
    }

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

    public function getSaveFieldsClass(): string
    {
        return SaveFields::class;
    }
}
