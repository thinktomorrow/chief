<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Filters\Filters;
use Thinktomorrow\Chief\Fields\FieldManager;
use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Management\Details\Details;
use Thinktomorrow\Chief\Management\Details\Sections;
use Thinktomorrow\Chief\Management\Assistants\Assistant;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

interface Manager extends FieldManager
{
    /**
     * Identifies this type of manager. This is the key that is set upon registration of the manager.
     * @return string
     */
    public function managerKey(): string;

    /**
     * Set the specific model to be managed.
     *
     * Either give a specific model to manage or if no parameter passed,
     * we will assume a generic model instance instead for e.g. create and store.
     *
     * @param $model
     * @return Manager
     */
    public function manage($model): Manager;

    public function isAssistedBy(string $assistant): bool;

    public function assistant(string $assistant): Assistant;

    public function assistants(): array;

    /**
     * Find an instance by id wrapped in a Manager
     *
     * @param $id
     * @return Manager
     */
    public function findManaged($id): Manager;

    /**
     * Get all managed models wrapped in a Manager
     * E.g. used for the index.
     *
     * @return Collection of ManagedModel or Paginator
     */
    public function indexCollection();

    /**
     * Static class name of the model class.
     * @return string
     */
    public function modelClass(): string;

    /**
     * Retrieve the managed model instance
     * @return ManagedModel
     */
    public function existingModel(): ManagedModel;

    /**
     * Assert that the model already exists (in database)
     * @return bool
     */
    public function hasExistingModel(): bool;

    public function route($verb): ?string;

    public function can($verb): bool;

    /**
     * @param $verb
     * @return Manager
     * @throws NotAllowedManagerRoute
     */
    public function guard($verb): self;

    /**
     * The manager fields enriched with any of the assistant specified fields.
     *
     * @return Fields
     */
    public function fieldsWithAssistantFields(): Fields;

    /**
     * This determines the arrangement of the manageable fields
     * on the create and edit forms. By default, all fields
     * are presented in their order of appearance
     *
     * @param null $key pinpoint to a specific field arrangement e.g. for create page.
     * @return FieldArrangement
     */
    public function fieldArrangement($key = null): FieldArrangement;

    /**
     * Collection of filters to be used on the admin index pages.
     *
     * @return Filters
     */
    public static function filters(): Filters;

    /**
     * Action to execute deletion of the model.
     * @return mixed
     */
    public function delete();

    /**
     * This method can be used to manipulate the store request payload
     * before being passed to the storing / updating the models.
     *
     * @param Request $request
     * @return Request
     */
    public function storeRequest(Request $request): Request;

    /**
     * This method can be used to manipulate the update request payload
     * before being passed to the storing / updating the models.
     *
     * @param Request $request
     * @return Request
     */
    public function updateRequest(Request $request): Request;

    /**
     * Information regarding a specific managed model instance.
     *
     * @return Details
     */
    public function details(): Details;

    /**
     * Information and custom display on the index listing such as
     * sidebar info, filters, search, title and so on.
     *
     * @return Sections
     */
    public static function sections(): Sections;
}
