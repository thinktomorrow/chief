<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Application\DeleteModel;
use Thinktomorrow\Chief\Fragments\Actions\AddFragmentModel;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FragmentAssistant
{
    abstract protected function fieldValidator(): FieldValidator;
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;
    abstract protected function guard(string $action, $model = null);

    public function routesFragmentAssistant(): array
    {
        return [
            ManagedRoute::get('fragment-edit', 'fragment/{fragment_id}/edit'),
            ManagedRoute::put('fragment-update', 'fragment/{fragment_id}/update'),
            ManagedRoute::post('fragment-status', 'fragment/{fragment_id}/status'),
            ManagedRoute::delete('fragment-delete', 'fragment/{fragment_id}'),

            ManagedRoute::post('fragment-share', 'fragment/{fragmentmodel_id}/share'),
            ManagedRoute::post('fragment-unshare', 'fragment/{fragmentmodel_id}/unshare'),

            ManagedRoute::get('fragment-create', 'fragment/{fragmentowner_type}/{fragmentowner_id}/create'),
            ManagedRoute::post('fragment-store', 'fragment/{fragmentowner_type}/{fragmentowner_id}'),
            ManagedRoute::post('fragment-add', 'fragment/{fragmentowner_type}/{fragmentowner_id}/{fragmentmodel_id}/add'),
            ManagedRoute::get('nested-fragment-create', 'nestedfragment/{fragmentmodel_id}/create'),
            ManagedRoute::post('nested-fragment-store', 'nestedfragment/{fragmentmodel_id}'),
        ];
    }

    public function routeFragmentAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! in_array($action, [
            'fragment-edit','fragment-update','fragment-delete',
            'fragment-create','fragment-store', 'fragment-status',
            'fragment-add', 'fragment-share','fragment-unshare',
        ])) {
            return null;
        }

        $modelKey = $this->managedModelClass()::managedModelKey();

        // model is owner for create endpoints
        if (in_array($action, ['fragment-create', 'fragment-store', 'fragment-add'])) {
            if (! $model || ! $model instanceof FragmentsOwner) {
                throw new \Exception('Fragment route definition for '.$action.' requires the owning Model as second argument.');
            }

            // fragment-add has second argument as the fragmentable
            if($action == 'fragment-add' && $parameters[0] instanceof Fragmentable) {
                $parameters[0] = $parameters[0]->fragmentModel()->id;
            }

            // Nested fragments
            if ($model instanceof Fragmentable && $model->isFragment()) {
                return route('chief.' . $modelKey . '.nested-' . $action, array_merge([
                    $model->fragmentModel()->id,
                ], $parameters));
            }

            return route('chief.' . $modelKey . '.' . $action, array_merge([
                $model::managedModelKey(),
                $model->modelReference()->id(),
            ], $parameters));
        }

        // Here model refers to the editable fragmentable
        if (! $model || ! $model instanceof Fragmentable) {
            throw new \Exception('Fragment route definition for '.$action.' requires the fragment model as second argument.');
        }

        return route('chief.' . $modelKey . '.' . $action, $model->fragmentModel()->id);
    }

    public function canFragmentAssistant(string $action, $model = null): bool
    {
        return in_array($action, [
            'fragment-edit','fragment-update','fragment-delete',
            'fragment-add', 'fragment-create', 'fragment-store',
            'fragment-status', 'fragment-share', 'fragment-unshare',
        ]);
    }

    public function nestedFragmentCreate(Request $request, $fragmentModelId)
    {
        $owner = $this->fragmentRepository->find($fragmentModelId);

        $fragmentable = $this->fragmentable();

        return view('chief::managers.fragments.create', [
            'manager' => $this,
            'owner' => $owner,
            'model' => $fragmentable,
            'fields' => $fragmentable->fields()->notTagged('edit'),
        ]);
    }

    public function fragmentCreate(Request $request, string $ownerKey, $ownerId)
    {
        $owner = $this->owner($ownerKey, $ownerId);
        $fragmentable = $this->fragmentable();

        return view('chief::managers.fragments.create', [
            'manager' => $this,
            'owner' => $owner,
            'model' => $fragmentable,
            'fields' => $fragmentable->fields()->notTagged('edit'),
        ]);
    }

    public function fragmentStore(Request $request, string $ownerKey, $ownerId)
    {
        $this->guard('fragment-store');

        $owner = $this->owner($ownerKey, $ownerId);
        $fragmentable = $this->fragmentable();

        $this->fieldValidator()->handle($fragmentable->fields()->notTagged('edit'), $request->all());

        // TODO: pass order with request
        // TODO: this is hacky, right Ben?
        $request->merge(['order' => intval($request->input('order'))]);

        $this->storeFragmentable($owner, $fragmentable, $request);

        // TODO: savefields for static fragment
        // Allow relations, translations, assets, ...

        return response()->json([
            'message' => 'fragment created',
            'data' => [],
        ], 201);
    }

    public function fragmentAdd(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        // TODO: need to account for nested fragment add as well.
        $owner = $this->owner($ownerKey, $ownerId);

        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        $request->merge(['order' => intval($request->input('order'))]);

        // 1. show in selectbox
        // 2. immediately add to fragments
        // 3. clear note on a shared fragment edit (or even a label)
        // 4. removeShare(fragmentId): this duplicates the fragment for all OR keep existing but make no longer shareable for other pages
        // 5. delete a shared fragment: deletes for all CONTEXTS
        // 6. extra: list all shared fragments on separate page (is it possible)

        $this->addFragmentable($owner, $fragmentable, $request);

        // TODO: savefields for static fragment
        // Allow relations, translations, assets, ...

        return response()->json([
            'message' => 'fragment added',
            'data' => [],
        ], 201);
    }

    public function nestedFragmentStore(Request $request, $fragmentModelId)
    {
        $this->guard('fragment-store');

        $owner = $this->fragmentRepository->find($fragmentModelId);
        $fragmentable = $this->fragmentable();

        $this->fieldValidator()->handle($fragmentable->fields()->notTagged('edit'), $request->all());

        // TODO: pass order with request
        $request->merge(['order' => 1]);

        $this->storeFragmentable($owner->fragmentModel(), $fragmentable, $request);

        // TODO: savefields for static fragment
        // Allow relations, translations, assets, ...

        return response()->json([
            'message' => 'nested fragment created',
            'data' => [],
        ], 201);
    }

    private function storeFragmentable(Model $owner, Fragmentable $fragmentable, Request $request): void
    {
        $fragmentable->saveFields($fragmentable->fields()->notTagged('edit'), $request->all(), $request->allFiles());

        $fragmentable->setFragmentModel(
            app(CreateFragmentModel::class)->create($owner, $fragmentable, $request->order)
        );
    }

    private function addFragmentable(Model $owner, Fragmentable $fragmentable, Request $request): void
    {
        app(AddFragmentModel::class)->handle(
            $owner,
            $fragmentable->fragmentModel(),
            $request->input('order', 0)
        );
    }

    public function fragmentEdit(Request $request, string $fragmentId)
    {
        $this->guard('fragment-edit');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        return view('chief::managers.fragments.edit', [
            'manager' => $this,
            'model' => $fragmentable,
            'fields' => $fragmentable->fields()->model($this->fragmentModel($fragmentable)),
        ]);
    }

    public function fragmentUpdate(Request $request, string $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        $this->fieldValidator()->handle($fragmentable->fields(), $request->all());

        // TODO: pass order with request
//        $request->merge(['order' => 1]);

        $this->fragmentModel($fragmentable)->saveFields($fragmentable->fields(), $request->all(), $request->allFiles());

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
            return redirect()->back()->with('messages.warning', $model->adminLabel('title') . ' is niet verwijderd.');
        }

        app(DeleteModel::class)->handle($model);

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminLabel('title') . '" is verwijderd.');
    }

    private function owner(string $ownerKey, $ownerId): FragmentsOwner
    {
        $ownerClass = app(Registry::class)->modelClass($ownerKey);

        return $ownerClass::withoutGlobalScopes()->find($ownerId);
    }

    /**
     * Which fragment model will the fields be saved to? This can be overwritten so that
     * also static fragments can store their values on the fragmentModel.
     */
    private function fragmentModel(Fragmentable $fragmentable)
    {
        return $fragmentable;
    }

    private function fragmentable(): Fragmentable
    {
        $modelClass = $this->managedModelClass();

        return new $modelClass();
    }
}
