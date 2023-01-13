<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableRepository;

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

    public function getInstanceAttributes(Request $request): array
    {
        return [];
    }

    public function isNestable(): bool
    {
        return false;
    }

    public function nestableRepository(): NestableRepository
    {
        // TODO: only fetch once!!!!! memoize the tree.
        return app()->makeWith(NestablePageRepository::class, ['modelClass' => static::class]);
    }
}
