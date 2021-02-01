<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\Management\Details\Details;
use Thinktomorrow\Chief\Management\Details\DetailSections;
use Thinktomorrow\Chief\Management\Assistants\AssistedManager;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;

interface Manager extends AssistedManager
{
    /**
     * Identifies this type of manager. This is the key that is set upon registration of the manager.
     *
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

    public function withoutPagination(): Manager;

    /**
     * Viewfile for the admin index listing of results
     * @return string
     */
    public function indexView(): string;

    /**
     * Any additional viewData to be passed on to the index view partial
     * @return array
     */
    public function indexViewData(): array;

    public function isManualSortable(): bool;

    /**
     * An empty, default instance of the managed model.
     * This is an instance that is newed up without being persisted.
     *
     * @return ManagedModel
     */
    public function modelInstance(): ManagedModel;

    /**
     * Retrieve the managed model instance
     *
     * @return ManagedModel
     */
    public function existingModel(): ManagedModel;

    /**
     * Assert that the model already exists (in database)
     *
     * @return bool
     */
    public function hasExistingModel(): bool;

    public function route($verb): ?string;

    public function can($verb): bool;

    /**
     * @param $verb
     * @return Manager
     * @throws \Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction
     */
    public function guard($verb): self;

    /**
     * The manager fields enriched with any of the assistant specified fields.
     *
     * @return Fields
     */
    public function fieldsWithAssistantFields(): Fields;

    /**
     * Collection of filters to be used on the admin index pages.
     *
     * @return Filters
     */
    public static function filters(): Filters;

    /**
     * Action to execute deletion of the model.
     *
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
     * @return DetailSections
     */
    public static function sections(): DetailSections;
}
