<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\Actions\AddFragmentModel;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Fragments\Actions\UnshareFragment;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyDetached;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicateFragment;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FragmentAssistant
{
    abstract protected function fieldValidator(): FieldValidator;

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    abstract protected function guard(string $action, $model = null);

    public function routesFragmentAssistant(): array
    {
        return [
            ManagedRoute::put('fragment-update', 'fragment/{fragment_id}/update'),
            ManagedRoute::post('fragment-status', 'fragment/{fragment_id}/status'),

            ManagedRoute::get('fragment-create', 'fragment/{fragmentowner_type}/{fragmentowner_id}/create'),
            ManagedRoute::post('fragment-store', 'fragment/{fragmentowner_type}/{fragmentowner_id}'),

            ManagedRoute::get('fragment-edit', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragment_id}/edit'),

            ManagedRoute::post('fragment-add', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/add'),
            ManagedRoute::post('fragment-copy', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/copy'),
            ManagedRoute::post('fragment-unshare', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/unshare'),
            ManagedRoute::delete('fragment-delete', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/remove'),

            // Nested routes are not used in the views, but only provided for the assistant to be able to deal with nested fragments.
            ManagedRoute::get('nested-fragment-create', 'nestedfragment/{fragmentowner_model_id}/create'),
            ManagedRoute::post('nested-fragment-store', 'nestedfragment/{fragmentowner_model_id}'),
            ManagedRoute::get('nested-fragment-edit', 'nestedfragment/{fragmentowner_model_id}/{fragment_id}/edit'),
            ManagedRoute::post('nested-fragment-add', 'nestedfragment/{fragmentowner_model_id}/{fragmentmodel_id}/add'),
            ManagedRoute::post('nested-fragment-copy', 'nestedfragment/{fragmentowner_model_id}/{fragmentmodel_id}/copy'),
            ManagedRoute::post('nested-fragment-unshare', 'nestedfragment/{fragmentowner_model_id}/{fragmentmodel_id}/unshare'),
            ManagedRoute::delete('nested-fragment-delete', 'nestedfragment/{fragmentowner_model_id}/{fragmentmodel_id}/remove'),
        ];
    }

    public function routeFragmentAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! in_array($action, [
            'fragment-edit',
            'fragment-update',
            'fragment-delete',
            'fragment-create',
            'fragment-store',
            'fragment-status',
            'fragment-add',
            'fragment-copy',
            'fragment-unshare',
            'fragment-delete',
        ])) {
            return null;
        }

        $modelKey = $this->managedModelClass()::managedModelKey();

        // model argument is owner for create endpoints
        if (in_array($action, ['fragment-create', 'fragment-store', 'fragment-edit', 'fragment-add', 'fragment-copy', 'fragment-unshare', 'fragment-delete'])) {
            if (! $model || ! $model instanceof FragmentsOwner) {
                throw new \Exception('Fragment route definition for ' . $action . ' requires a FragmentsOwner Model as second argument.');
            }

            // Some fragment edit/update actions have second argument as the fragmentable
            if (in_array($action, ['fragment-edit', 'fragment-add', 'fragment-copy', 'fragment-unshare', 'fragment-delete']) && $parameters[0] instanceof Fragmentable) {
                $parameters[0] = $parameters[0]->fragmentModel()->id;
            }

            // Nested fragments routes
            if ($model instanceof Fragmentable) {
                return route('chief.' . $modelKey . '.nested-' . $action, array_merge([$model->fragmentModel()->id], $parameters));
            }

            return route('chief.' . $modelKey . '.' . $action, array_merge([
                $model::managedModelKey(),
                $model->modelReference()->id(),
            ], $parameters));
        }

        // Here model refers to the editable fragmentable
        if (! $model || ! $model instanceof Fragmentable) {
            throw new \Exception('Fragment route definition for ' . $action . ' requires the fragment model as second argument.');
        }

        return route('chief.' . $modelKey . '.' . $action, $model->fragmentModel()->id);
    }

    public function canFragmentAssistant(string $action, $model = null): bool
    {
        return in_array($action, [
            'fragment-edit',
            'fragment-update',
            'fragment-delete',
            'fragment-add',
            'fragment-copy',
            'fragment-create',
            'fragment-store',
            'fragment-status',
            'fragment-unshare',
            'fragment-delete',
        ]);
    }

    public function fragmentCreate(Request $request, string $ownerKey, $ownerId)
    {
        return $this->renderFragmentCreate($this->ownerModel($ownerKey, $ownerId), $request->input('order', 0));
    }

    public function nestedFragmentCreate(Request $request, $fragmentModelId)
    {
        return $this->renderFragmentCreate($this->fragmentRepository->find($fragmentModelId), $request->input('order', 0));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    private function renderFragmentCreate($owner, $order)
    {
        $fragmentable = $this->fragmentable();

        return view('chief::manager.cards.fragments.create', [
            'manager' => $this,
            'owner' => $owner,
            'model' => $fragmentable,
            'fields' => Fields::make($fragmentable->fields())->notTagged('edit'),
            'order' => $order,
        ]);
    }

    public function fragmentStore(Request $request, string $ownerKey, $ownerId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentStore($this->ownerModel($ownerKey, $ownerId), $request);
    }

    public function nestedFragmentStore(Request $request, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentStore($this->fragmentRepository->find($fragmentModelId)->fragmentModel(), $request);
    }

    private function handleFragmentStore($ownerModel, Request $request)
    {
        $fragmentable = $this->fragmentable();

        $this->fieldValidator()->handle(Fields::make($fragmentable->fields())->notTagged('edit'), $request->all());

        $request->merge(['order' => (int)$request->input('order', 0)]);

        $this->storeFragmentable($ownerModel, $fragmentable, $request);

        return response()->json([
            'message' => 'fragment created',
            'data' => [],
        ], 201);
    }

    private function storeFragmentable(Model $owner, Fragmentable $fragmentable, Request $request): void
    {
        $fragmentable->setFragmentModel(app(CreateFragmentModel::class)->create($owner, $fragmentable, $request->order));

        $fragmentable->fragmentModel()->saveFields(Fields::make($fragmentable->fields())->notTagged('edit'), $request->all(), $request->allFiles());
    }

    public function fragmentAdd(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentAdd($this->ownerModel($ownerKey, $ownerId), (int) $fragmentModelId, (int) $request->input('order', 0));
    }

    public function nestedFragmentAdd(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentAdd($this->fragmentRepository->find((int) $fragmentOwnerModelId)->fragmentModel(), (int) $fragmentModelId, (int) $request->input('order', 0));
    }

    private function handleFragmentAdd(Model $ownerModel, int $fragmentModelId, int $order)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        try {
            app(AddFragmentModel::class)->handle($ownerModel, $fragmentable->fragmentModel(), $order);
        } catch (FragmentAlreadyAdded $e) {
            return response()->json([
                'message' => 'fragment ['.$fragmentModelId.'] is already added',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'fragment ['.$fragmentModelId.'] added',
            'data' => [],
        ], 201);
    }

    public function fragmentCopy(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentCopy($this->ownerModel($ownerKey, $ownerId), (int) $fragmentModelId, $request->input('order', 0), (true == $request->input('hardcopy')));
    }

    public function nestedFragmentCopy(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentCopy($this->fragmentRepository->find($fragmentOwnerModelId)->fragmentModel(), (int) $fragmentModelId, $request->input('order', 0), (true == $request->input('hardcopy')));
    }

    private function handleFragmentCopy(Model $ownerModel, int $fragmentModelId, int $order, $hardCopy = false)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        app(DuplicateFragment::class)->handle($ownerModel, $fragmentable->fragmentModel(), $order, $hardCopy);

        return response()->json([
            'message' => 'fragment ['.$fragmentModelId.'] added as copy',
            'data' => [],
        ], 201);
    }

    public function fragmentUnshare(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentUnshare($this->ownerModel($ownerKey, $ownerId), (int) $fragmentModelId);
    }

    public function nestedFragmentUnshare(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentUnshare($this->fragmentRepository->find($fragmentOwnerModelId)->fragmentModel(), (int) $fragmentModelId);
    }

    private function handleFragmentUnshare(Model $ownerModel, int $fragmentModelId)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        app(UnshareFragment::class)->handle($ownerModel, $fragmentable->fragmentModel());

        return response()->json([
            'message' => 'fragment ['.$fragmentModelId.'] detached as shared and now available as isolated fragment',
            'data' => [],
        ], 201);
    }

    public function fragmentDelete(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-delete');

        return $this->handleFragmentDelete($this->ownerModel($ownerKey, $ownerId), (int) $fragmentModelId);
    }

    public function nestedFragmentDelete(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-delete');

        return $this->handleFragmentDelete($this->fragmentRepository->find((int) $fragmentOwnerModelId)->fragmentModel(), (int) $fragmentModelId);
    }

    private function handleFragmentDelete(Model $ownerModel, int $fragmentModelId)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        try {
            app(DetachFragment::class)->handle($ownerModel, $fragmentable->fragmentModel());
        } catch (FragmentAlreadyDetached $e) {
            return response()->json([
                'message' => 'fragment ['.$fragmentModelId.'] is already removed.',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'fragment ['.$fragmentModelId.'] removed',
            'data' => [],
        ]);
    }

    public function fragmentEdit(Request $request, string $ownerKey, $ownerId, $fragmentId)
    {
        $fragmentable = $this->fragmentRepository->find((int) $fragmentId);

        return $this->handleFragmentEdit($request, $this->ownerModel($ownerKey, $ownerId), $fragmentable);
    }

    public function nestedFragmentEdit(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $fragmentable = $this->fragmentRepository->find((int) $fragmentModelId);

        return $this->handleFragmentEdit($request, $this->fragmentRepository->find((int) $fragmentOwnerModelId), $fragmentable);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function handleFragmentEdit(Request $request, FragmentsOwner $ownerModel, Fragmentable $fragmentable)
    {
        $this->guard('fragment-edit', $fragmentable);

        return view('chief::manager.cards.fragments.edit', [
            'manager' => $this,
            'owner' => $ownerModel,
            'model' => $fragmentable,
            'fields' => Fields::make($fragmentable->fields())->model($fragmentable->fragmentModel()),
        ]);
    }

    public function fragmentUpdate(Request $request, $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find((int) $fragmentId);

        $this->fieldValidator()->handle(Fields::make($fragmentable->fields()), $request->all());

        $fragmentable->fragmentModel()->saveFields(Fields::make($fragmentable->fields()), $request->all(), $request->allFiles());

        return response()->json([
            'message' => 'fragment updated',
            'data' => [],
        ], 200);
    }

    public function fragmentStatus(Request $request, $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find((int) $fragmentId);

        $fragmentable->fragmentModel()->update(['online_status' => ! ! $request->input('online_status')]);

        return response()->json([
            'message' => 'fragment online status updated',
            'data' => [],
        ]);
    }

    /**
     * @param string $ownerKey
     * @param $ownerId
     * @return Model (FragmentsOwner)
     */
    private function ownerModel(string $ownerKey, $ownerId): Model
    {
        $ownerClass = $this->registry->modelClass($ownerKey);

        return $ownerClass::withoutGlobalScopes()->find($ownerId);
    }

    private function fragmentable(): Fragmentable
    {
        $modelClass = $this->managedModelClass();

        return new $modelClass();
    }
}
