<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Fields\Field;

interface ModelManager
{
    /**
     * Set the specific model to be managed.
     *
     * Either give a specific model to manage or if no parameter passed,
     * we will assume a generic model instance instead for e.g. create and store.
     *
     * @param ManagedModel|null $model
     * @return ModelManager
     */
    public function manage(ManagedModel $model = null): ModelManager;

    /**
     * Find an instance by id.
     *
     * @param $id
     * @return null|ModelManager
     */
    public static function findById($id): ?ModelManager;

    /**
     * Get all ModelManagers that should be managed.
     * E.g. used for the index.
     *
     * @return Collection of ModelManager
     */
    public static function findAllManaged(): Collection;

    public function route($verb): ?string;

    public function can($verb): bool;

    /**
     * The set of fields that should be manageable for a certain model.
     *
     * Additionally, you should:
     * 2. Make sure to setup the proper migrations and
     * 3. For a translatable field you should add this field to the translatable values of the model as well.
     *
     * @return Field[]
     */
    public function fields(): array;

    /**
     * @param Field|string $field
     * @return mixed
     */
    public function getFieldValue($field);

    public function setField(Field $field, Request $request);

    public function saveFields(): ModelManager;

    public function renderField(Field $field);

    public function delete();

    /**
     * Details and display data regarding the Manager and the model in general.
     *
     * @return ManagerDetails
     */
    public function managerDetails(): ManagerDetails;

    /**
     * Information regarding a specific managed model instance.
     *
     * @return ManagedModelDetails
     */
    public function managedModelDetails(): ManagedModelDetails;
}