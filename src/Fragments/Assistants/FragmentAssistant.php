<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Actions\AddFragmentModel;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Fragments\Actions\RemoveFragmentModel;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyRemoved;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Actions\DeleteModel;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicateFragment;

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
            ManagedRoute::delete('fragment-delete', 'fragment/{fragment_id}'),

            ManagedRoute::post('fragment-share', 'fragment/{fragmentmodel_id}/share'),
            ManagedRoute::post('fragment-unshare', 'fragment/{fragmentmodel_id}/unshare'),

            ManagedRoute::get('fragment-create', 'fragment/{fragmentowner_type}/{fragmentowner_id}/create'),
            ManagedRoute::post('fragment-store', 'fragment/{fragmentowner_type}/{fragmentowner_id}'),

            ManagedRoute::get('fragment-edit', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragment_id}/edit'),

            ManagedRoute::post('fragment-add', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/add'),
            ManagedRoute::post('fragment-copy', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/copy'),
            ManagedRoute::delete('fragment-remove', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/remove'),

            // Nested routes are not used in the views, but only provided for the assistant to be able to deal with nested fragments.
            ManagedRoute::get('nested-fragment-create', 'nestedfragment/{fragmentowner_model_id}/create'),
            ManagedRoute::post('nested-fragment-store', 'nestedfragment/{fragmentowner_model_id}'),
            ManagedRoute::get('nested-fragment-edit', 'nestedfragment/{fragmentowner_model_id}/{fragment_id}/edit'),
            ManagedRoute::post('nested-fragment-add', 'nestedfragment/{fragmentowner_model_id}/{fragmentmodel_id}/add'),
            ManagedRoute::post('nested-fragment-copy', 'nestedfragment/{fragmentowner_model_id}/{fragmentmodel_id}/copy'),
            ManagedRoute::delete('nested-fragment-remove', 'nestedfragment/{fragmentowner_model_id}/{fragmentmodel_id}/remove'),
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
            'fragment-share',
            'fragment-unshare',
            'fragment-remove',
        ])) {
            return null;
        }

        $modelKey = $this->managedModelClass()::managedModelKey();

        // model argument is owner for create endpoints
        if (in_array($action, ['fragment-create', 'fragment-store', 'fragment-edit', 'fragment-add', 'fragment-copy', 'fragment-remove'])) {
            if (! $model || ! $model instanceof FragmentsOwner) {
                throw new \Exception('Fragment route definition for ' . $action . ' requires a FragmentsOwner Model as second argument.');
            }

            // Some fragment edit/update actions have second argument as the fragmentable
            if (in_array($action, ['fragment-edit', 'fragment-add', 'fragment-copy', 'fragment-remove']) && $parameters[0] instanceof Fragmentable) {
                $parameters[0] = $parameters[0]->fragmentModel()->id;
            }

            // Nested fragments routes
            if ($model instanceof Fragmentable && $model->isFragment()) {
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
            'fragment-share',
            'fragment-unshare',
            'fragment-remove',
        ]);
    }

    public function fragmentCreate(Request $request, string $ownerKey, $ownerId)
    {
        return $this->renderFragmentCreate($this->ownerModel($ownerKey, $ownerId));
    }

    public function nestedFragmentCreate(Request $request, $fragmentModelId)
    {
        return $this->renderFragmentCreate($this->fragmentRepository->find($fragmentModelId));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    private function renderFragmentCreate($owner)
    {
        $fragmentable = $this->fragmentable();

        return view('chief::manager.cards.fragments.create', [
            'manager' => $this,
            'owner' => $owner,
            'model' => $fragmentable,
            'fields' => Fields::make($fragmentable->fields())->notTagged('edit'),
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
        $fragmentable->saveFields(Fields::make($fragmentable->fields())->notTagged('edit'), $request->all(), $request->allFiles());

        $fragmentable->setFragmentModel(app(CreateFragmentModel::class)->create($owner, $fragmentable, $request->order));
    }

    public function fragmentAdd(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentAdd($this->ownerModel($ownerKey, $ownerId), $fragmentModelId, $request->input('order', 0));
    }

    public function nestedFragmentAdd(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentAdd($this->fragmentRepository->find($fragmentOwnerModelId)->fragmentModel(), $fragmentModelId, $request->input('order', 0));
    }

    private function handleFragmentAdd(Model $ownerModel, string $fragmentModelId, int $order)
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

        return $this->handleFragmentCopy($this->ownerModel($ownerKey, $ownerId), $fragmentModelId, $request->input('order', 0));
    }

    public function nestedFragmentCopy(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentCopy($this->fragmentRepository->find($fragmentOwnerModelId)->fragmentModel(), $fragmentModelId, $request->input('order', 0));
    }

    private function handleFragmentCopy(Model $ownerModel, string $fragmentModelId, int $order)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        app(DuplicateFragment::class)->handle($ownerModel, $fragmentable->fragmentModel(), $order);

        return response()->json([
            'message' => 'fragment ['.$fragmentModelId.'] added as copy',
            'data' => [],
        ], 201);
    }

    public function fragmentRemove(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-delete');

        return $this->handleFragmentRemove($this->ownerModel($ownerKey, $ownerId), $fragmentModelId);
    }

    public function nestedFragmentRemove(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-delete');

        return $this->handleFragmentRemove($this->fragmentRepository->find($fragmentOwnerModelId)->fragmentModel(), $fragmentModelId);
    }

    private function handleFragmentRemove(Model $ownerModel, string $fragmentModelId)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        try {
            app(RemoveFragmentModel::class)->handle($ownerModel, $fragmentable->fragmentModel(), );
        } catch (FragmentAlreadyRemoved $e) {
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fragmentEdit(Request $request, string $ownerKey, $ownerId, string $fragmentId)
    {
        $this->guard('fragment-edit');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        return view('chief::manager.cards.fragments.edit', [
            'manager' => $this,
            'owner' => $this->ownerModel($ownerKey, $ownerId),
            'model' => $fragmentable,
            'fields' => Fields::make($fragmentable->fields())->model($this->fragmentModel($fragmentable)),
        ]);
    }

    public function fragmentUpdate(Request $request, string $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        $this->fieldValidator()->handle(Fields::make($fragmentable->fields()), $request->all());

        // TODO: pass order with request
//        $request->merge(['order' => 1]);

        $this->fragmentModel($fragmentable)->saveFields(Fields::make($fragmentable->fields()), $request->all(), $request->allFiles());

        return response()->json([
            'message' => 'fragment updated',
            'data' => [],
        ], 200);
    }

    public function fragmentShare(Request $request, string $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        $fragmentable->fragmentModel()->update(['shared' => 1]);

        return response()->json([
            'message' => 'fragment is now shared',
            'data' => [],
        ]);
    }

    public function fragmentUnshare(Request $request, string $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        $fragmentable->fragmentModel()->update(['shared' => 0]);

        return response()->json([
            'message' => 'fragment is no longer shared.',
            'data' => [],
        ]);
    }

    public function fragmentStatus(Request $request, string $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        $fragmentable->fragmentModel()->update(['online_status' => ! ! $request->input('online_status')]);

        return response()->json([
            'message' => 'fragment online status updated',
            'data' => [],
        ]);
    }

    public function fragmentDelete($id, Request $request)
    {
        $this->guard('fragment-delete');

        $model = $this->managedModelClass()::withoutGlobalScopes()->findOrFail($id);

        if ($request->get('deleteconfirmation') !== 'DELETE') {
            return redirect()->back()->with('messages.warning', $model->adminConfig()->getPageTitle() . ' is niet verwijderd.');
        }

        app(DeleteModel::class)->handle($model);

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminConfig()->getPageTitle() . '" is verwijderd.');
    }

    private function ownerModel(string $ownerKey, $ownerId): Model
    {
        $ownerClass = app(Registry::class)->modelClass($ownerKey);

        return $ownerClass::withoutGlobalScopes()->find($ownerId);
    }

    /**
     * Which fragment model will the fields be saved to? This can be overwritten so that
     * also static fragments can store their values on the fragmentModel.
     *
     * @return \Thinktomorrow\Chief\Fragments\Fragmentable
     */
    private function fragmentModel(Fragmentable $fragmentable): \Thinktomorrow\Chief\Fragments\Fragmentable
    {
        return $fragmentable;
    }

    private function fragmentable(): Fragmentable
    {
        $modelClass = $this->managedModelClass();

        return new $modelClass();
    }
}
