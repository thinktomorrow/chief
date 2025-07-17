<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Resource;

use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Fragments\Fragment;
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

    public function getPluralLabel(): string
    {
        return (new ResourceKeyFormat(static::modelClassName()))->getPluralLabel();
    }

    public function field($model, string $key): Field
    {
        $fieldModel = $model instanceof Fragment ? $model->getFragmentModel() : $model;

        return Fields::makeWithoutFlatteningNestedFields($this->fields($model))->find($key)->model($fieldModel);
    }

    public function nestedField($model, string $key): Field
    {
        $fieldModel = $model instanceof Fragment ? $model->getFragmentModel() : $model;

        // TODO: this fails when the nested field has the same key as one of the other fields.
        return Fields::make($this->fields($model))->find($key)->model($fieldModel);
    }

    abstract public function fields($model): iterable;

    public function getSaveFieldsClass(): string
    {
        return SaveFields::class;
    }

    public function getAttributesOnCreate(array $input = []): array
    {
        return [];
    }

    public function prepareInputOnCreate(array $input): array
    {
        return $input;
    }
}
