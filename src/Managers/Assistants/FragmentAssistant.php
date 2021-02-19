<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\ManagedModels\Application\DeleteModel;

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

            ManagedRoute::get('fragment-create', 'fragment/{fragmentowner_type}/{fragmentowner_id}/create'),
            ManagedRoute::post('fragment-store', 'fragment/{fragmentowner_type}/{fragmentowner_id}'),
            ManagedRoute::get('nested-fragment-create', 'nestedfragment/{fragmentmodel_id}/create'),
            ManagedRoute::post('nested-fragment-store', 'nestedfragment/{fragmentmodel_id}'),
        ];
    }

    public function routeFragmentAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if(!in_array($action, ['fragment-edit','fragment-update','fragment-delete','fragment-create','fragment-store', 'fragment-status'])) {
            return null;
        }

        $modelKey = $this->managedModelClass()::managedModelKey();

        // model is owner for create endpoints
        if(in_array($action, ['fragment-create', 'fragment-store'])) {
            if (!$model || !$model instanceof FragmentsOwner) {
                throw new \Exception('Fragment route definition for '.$action.' requires the owning Model as second argument.');
            }

            // Nested fragments
            if($model instanceof Fragmentable && $model->isFragment()) {
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
        if (!$model || !$model instanceof Fragmentable) {
            throw new \Exception('Fragment route definition for '.$action.' requires the fragment model as second argument.');
        }

        return route('chief.' . $modelKey . '.' . $action, $model->fragmentModel()->id);
    }

    public function canFragmentAssistant(string $action, $model = null): bool
    {
        return in_array($action, [
            'fragment-edit','fragment-update','fragment-delete','fragment-create','fragment-store', 'fragment-status',
        ]);
    }

    public function nestedFragmentCreate(Request $request, $fragmentModelId)
    {
        $owner = $this->fragmentRepository->find($fragmentModelId);

        $fragmentable = $this->fragmentable();

        return view('chief::managers.fragments.create', [
            'manager'    => $this,
            'owner'      => $owner,
            'model'      => $fragmentable,
            'fields'     => $fragmentable->fields()->notTagged('edit'),
        ]);
    }

    public function fragmentCreate(Request $request, string $ownerKey, $ownerId)
    {
        $owner = $this->owner($ownerKey, $ownerId);
        $fragmentable = $this->fragmentable();

        return view('chief::managers.fragments.create', [
            'manager'    => $this,
            'owner'      => $owner,
            'model'      => $fragmentable,
            'fields'     => $fragmentable->fields()->notTagged('edit')
        ]);
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

    private function storeFragmentable(Model $owner, Fragmentable $fragmentable, Request $request): void
    {
        $fragmentable->saveFields($fragmentable->fields()->notTagged('edit'), $request->all(), $request->allFiles());

        $fragmentable->setFragmentModel(
            app(CreateFragmentModel::class)->create($owner, $fragmentable, $request->order)
        );
    }

    public function fragmentEdit(Request $request, string $fragmentId)
    {
        $this->guard('fragment-edit');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        return view('chief::managers.fragments.edit', [
            'manager'    => $this,
            'model'      => $fragmentable,
            'fields'     => $fragmentable->fields()->notTagged('create')->model($this->fragmentModel($fragmentable)),
        ]);
    }

    public function fragmentUpdate(Request $request, string $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        $this->fieldValidator()->handle($fragmentable->fields()->notTagged('create'), $request->all());

        // TODO: pass order with request
//        $request->merge(['order' => 1]);

        $this->fragmentModel($fragmentable)->saveFields($fragmentable->fields()->notTagged('create'), $request->all(), $request->allFiles());

        return response()->json([
            'message' => 'fragment updated',
            'data' => [],
        ], 200);
    }

    public function fragmentStatus(Request $request, string $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find($fragmentId);

        $fragmentable->fragmentModel()->update(['online_status' => !!$request->input('online_status')]);

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
