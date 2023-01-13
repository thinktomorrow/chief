<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableRepository;

interface Resource
{
    /**
     * Unique identifier in the chief application.
     */
    public static function resourceKey(): string;

    public static function modelClassName(): string;

    /**
     * The label that represents this resource in the admin.
     */
    public function getLabel(): string;

    public function fields($model): iterable;

    public function field($model, string $key): Field;

    public function getSaveFieldsClass(): string;

    /**
     * Attributes that will be set on a new created model.
     * Used in the create/store flow.
     *
     * @return array
     */
    public function getInstanceAttributes(Request $request): array;

    /**
     * Nestable models will have a different UI in backend
     * and allow for vine retrieval on the frontend
     *
     * @return bool
     */
    public function isNestable(): bool;

    // TODO: can be remove this from the model? Maybe just add nestable repository classname?
    public function nestableRepository(): ?NestableRepository;
}
