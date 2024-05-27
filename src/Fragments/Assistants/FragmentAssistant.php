<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\Actions\DuplicateFragment;
use Thinktomorrow\Chief\Fragments\Actions\IsolateFragment;
use Thinktomorrow\Chief\Fragments\Actions\PutFragmentOffline;
use Thinktomorrow\Chief\Fragments\Actions\PutFragmentOnline;
use Thinktomorrow\Chief\Fragments\Events\FragmentUpdated;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyDetached;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\FragmentStatus;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FragmentAssistant
{
    public function routesFragmentAssistant(): array
    {
        return [
            ManagedRoute::put('fragment-update', 'fragment/{fragment_id}/update'),
            ManagedRoute::post('fragment-status', 'fragment/{fragment_id}/status'),

            ManagedRoute::get('fragment-create', 'fragment/{context_id}/create'),
            ManagedRoute::post('fragment-store', 'fragment/{context_id}'),

            ManagedRoute::get('fragment-edit', 'fragment/{context_id}/{fragment_id}/edit'),

            ManagedRoute::post('fragment-add', 'fragment/{context_id}/{fragmentmodel_id}/add'),
            ManagedRoute::post('fragment-copy', 'fragment/{context_id}/{fragmentmodel_id}/copy'),
            ManagedRoute::post('fragment-unshare', 'fragment/{context_id}/{fragmentmodel_id}/unshare'),
            ManagedRoute::delete('fragment-delete', 'fragment/{context_id}/{fragmentmodel_id}/remove'),

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

        $modelKey = $this->resource::resourceKey();

        // model argument is Context model for create endpoints
        if (in_array($action, ['fragment-create', 'fragment-store', 'fragment-edit', 'fragment-add', 'fragment-copy', 'fragment-unshare', 'fragment-delete'])) {
            if (! $model || ! $model instanceof ContextModel) {
                throw new Exception('Fragment route definition for ' . $action . ' requires a Context Model as second argument.');
            }

            // Some fragment edit/update actions have second argument as the fragmentable
            if (in_array($action, ['fragment-edit', 'fragment-add', 'fragment-copy', 'fragment-unshare', 'fragment-delete']) && $parameters[0] instanceof Fragment) {
                $parameters[0] = $parameters[0]->fragmentModel()->id;
            }

            // Nested fragments routes
            if ($model instanceof Fragment) {
                return route('chief.' . $modelKey . '.nested-' . $action, array_merge([$model->fragmentModel()->id], $parameters));
            }

            return route('chief.' . $modelKey . '.' . $action, array_merge([
                $this->registry->findResourceByModel($model::class)::resourceKey(),
                $model->modelReference()->id(),
            ], $parameters));
        }

        if (! $model) {
            throw new Exception('Fragment route definition for ' . $action . ' requires a Model or Fragmentable as second argument.');
        }

        $modelId = $model instanceof Fragment ? $model->fragmentModel()->id : $model->id;

        return route('chief.' . $modelKey . '.' . $action, $modelId);
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

    public function fragmentCreate(Request $request, $contextId)
    {
        return $this->renderFragmentCreate($this->findContextModel($contextId), $request->input('order', 0));
    }

    /**
     * @return Factory|\Illuminate\Contracts\View\View
     */
    private function renderFragmentCreate(ContextModel $context, $order)
    {
        $fragmentable = $this->fragmentable();

        $forms = Forms::make($fragmentable->fields($fragmentable))
            ->fillModel($fragmentable->fragmentModel())
            ->fillFields($this, $fragmentable->fragmentModel())
            ->eachForm(function (Form $form) use ($fragmentable, $context) {
                $form->action($this->route('fragment-store', $context))
                    ->refreshUrl('');
            });

        View::share('manager', $this);
        View::share('model', $fragmentable);
        View::share('resource', $this->resource);
        View::share('context', $context);
        View::share('owner', $context->getOwner());
        View::share('forms', $forms);
        View::share('order', $order);

        return view('chief::manager.windows.fragments.create');
    }

    private function fragmentable(): Fragment
    {
        return app($this->managedModelClass());
    }

    private function findContextModel($contextId): ContextModel
    {
        return ContextModel::find($contextId);
    }

    public function nestedFragmentCreate(Request $request, $fragmentModelId)
    {
        return $this->renderFragmentCreate($this->fragmentRepository->find($fragmentModelId), $request->input('order', 0));
    }

    public function fragmentStore(Request $request, string $ownerKey, $ownerId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentStore($this->ownerModel($ownerKey, $ownerId), $request);
    }

    abstract protected function guard(string $action, $model = null);

    private function handleFragmentStore($ownerModel, Request $request)
    {
        $fragmentable = $this->fragmentable();

        // admin_locales -> Locales for save / validation -> which fields to validate.
        // site_locales -> Locales for visibility / associatedFragment -> where to show online

        // site_locales
        // admin_locales
        // -> always the same, except for shared fragments, which can have diffs.

        // site_locales => UpdateAttachedFragment::updateLocales();
        // admin_locales => not saved - only passed to validation
        // method on forms: fillLocalesIfEmpty() ?

        // Locales are passed along the request as well.
        //        if ($request->input('admin_locales')) {
        //            $fragmentable->fragmentModel()->setLocales($request->input('locales'));
        //        }

        $fields = Forms::make($fragmentable->fields($fragmentable))
            ->fillLocalesIfEmpty((array)$request->input('admin_locales', []))
            ->fillModel($fragmentable->fragmentModel())
            ->getFields()
            ->notTagged(['edit', 'not-on-create']);

        $this->fieldValidator()->handle($fields, $request->all());

        $request->merge(['order' => (int)$request->input('order', 0)]);

        $fragmentable = $this->storeFragmentable($ownerModel, $fragmentable, $request);

        $redirectTo = null;

        // If the fragment is a fragment owner ( = has nested fragments), we'll show the edit page of this fragment after creation
        // By default other fragments will return to the main edit page after being created.
        if ($fragmentable instanceof FragmentsOwner) {
            $redirectTo = ($ownerModel instanceof FragmentModel)
                ? $this->route('nested-fragment-edit', $ownerModel->id, $fragmentable->fragmentModel()->id)
                : $this->route('fragment-edit', $ownerModel, $fragmentable);
        }

        return response()->json([
            'message' => 'fragment created',
            'sidebar_redirect_to' => $redirectTo,
            'data' => [
                'fragmentmodel_id' => $fragmentable->fragmentModel()->id,
            ],
        ], 201);
    }

    abstract protected function fieldValidator(): FieldValidator;

    private function storeFragmentable(Model $owner, Fragment $fragmentable, Request $request): Fragment
    {
        $fragmentable->setFragmentModel(app(CreateFragment::class)->create($owner, $fragmentable, $request->order, [], $request->input('locales')));

        app($this->resource->getSaveFieldsClass())->save(
            $fragmentable->fragmentModel(),
            Fields::make($fragmentable->fields($fragmentable))->notTagged('edit'),
            $request->all(),
            $request->allFiles()
        );

        return $fragmentable;
    }

    /**
     * @param $ownerId
     *
     * @return Model (FragmentsOwner)
     */
    private function ownerModel(string $ownerKey, $ownerId): Model
    {
        $ownerClass = $this->registry->resource($ownerKey)::modelClassName();

        return $ownerClass::withoutGlobalScopes()->find($ownerId);
    }

    public function nestedFragmentStore(Request $request, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentStore($this->fragmentRepository->find($fragmentModelId)->fragmentModel(), $request);
    }

    public function fragmentEdit(Request $request, string $ownerKey, $ownerId, $fragmentId)
    {
        $fragmentable = $this->fragmentRepository->find((int)$fragmentId);

        return $this->handleFragmentEdit($request, $this->ownerModel($ownerKey, $ownerId), $fragmentable);
    }

    /**
     * @return Factory|\Illuminate\Contracts\View\View
     */
    public function handleFragmentEdit(Request $request, FragmentsOwner $ownerModel, Fragment $fragmentable)
    {
        $this->guard('fragment-edit', $fragmentable);

        $forms = Forms::make($fragmentable->fields($fragmentable))
            ->fillModel($fragmentable->fragmentModel())
            ->fillFields($this, $fragmentable->fragmentModel())
            ->eachForm(function (Form $form) use ($fragmentable, $ownerModel) {
                $form->action($this->route('fragment-update', $fragmentable->fragmentModel(), $ownerModel), 'PUT');
            });

        View::share('manager', $this);
        View::share('model', $fragmentable);
        View::share('owner', $ownerModel);
        View::share('resource', $this->resource);
        View::share('forms', $forms);

        return $fragmentable->adminView();
    }

    public function nestedFragmentEdit(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $fragmentable = $this->fragmentRepository->find((int)$fragmentModelId);

        return $this->handleFragmentEdit($request, $this->fragmentRepository->find((int)$fragmentOwnerModelId), $fragmentable);
    }

    public function fragmentUpdate(Request $request, $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find((int)$fragmentId);

        // Locales for save / validation -> which fields to validate.
        // Locales for visibility / associatedFragment -> where to show online

        // Locales are passed along the request as well to match the current model-fragment context.
        if ($request->input('locales')) {
            $fragmentable->fragmentModel()->setLocales($request->input('locales'));
        }

        // Validate only the locales for given context
        $forms = Forms::make($fragmentable->fields($fragmentable))
            ->fillModel($fragmentable->fragmentModel());
        //            ->find($tag) // TODO: use FormsAssistant for Fragments as well...

        $this->fieldValidator()->handle($forms->getFields(), $request->all());

        // Now set all locales for fields that require locales so that all values are saved on the fragment
        $fragmentable->fragmentModel()->setLocales(ChiefSites::getLocales());
        $fields = $forms->fillModel($fragmentable->fragmentModel())->getFields();

        app($this->resource->getSaveFieldsClass())->save($fragmentable->fragmentModel(), $fields, $request->all(), $request->allFiles());

        //        app(UpdateAssociatedFragment::class)->handle();

        event(new FragmentUpdated($fragmentable->fragmentModel()->id));

        return response()->json([
            'message' => 'fragment updated',
            'data' => [],
        ], 200);
    }

    public function fragmentAdd(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentAdd($this->ownerModel($ownerKey, $ownerId), (int)$fragmentModelId, (int)$request->input('order', 0));
    }

    private function handleFragmentAdd(Model $ownerModel, int $fragmentModelId, int $order)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        try {
            app(AttachFragment::class)->handle($ownerModel, $fragmentable->fragmentModel(), $order);
        } catch (FragmentAlreadyAdded $e) {
            return response()->json([
                'message' => 'fragment [' . $fragmentModelId . '] is already added',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'fragment [' . $fragmentModelId . '] added',
            'data' => [],
        ], 201);
    }

    public function nestedFragmentAdd(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentAdd($this->fragmentRepository->find((int)$fragmentOwnerModelId)->fragmentModel(), (int)$fragmentModelId, (int)$request->input('order', 0));
    }

    public function fragmentCopy(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentCopy($this->ownerModel($ownerKey, $ownerId), (int)$fragmentModelId, $request->input('order', 0), (true == $request->input('hardcopy')));
    }

    private function handleFragmentCopy(Model $ownerModel, int $fragmentModelId, int $order, $hardCopy = false)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        app(DuplicateFragment::class)->handle($ownerModel, $fragmentable->fragmentModel(), $order, $hardCopy);

        return response()->json([
            'message' => 'fragment [' . $fragmentModelId . '] added as copy',
            'data' => [],
        ], 201);
    }

    public function nestedFragmentCopy(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentCopy($this->fragmentRepository->find($fragmentOwnerModelId)->fragmentModel(), (int)$fragmentModelId, $request->input('order', 0), (true == $request->input('hardcopy')));
    }

    public function fragmentUnshare(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentUnshare($this->ownerModel($ownerKey, $ownerId), (int)$fragmentModelId);
    }

    private function handleFragmentUnshare(Model $ownerModel, int $fragmentModelId)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        app(IsolateFragment::class)->handle($ownerModel, $fragmentable->fragmentModel());

        return response()->json([
            'message' => 'fragment [' . $fragmentModelId . '] detached as shared and now available as isolated fragment',
            'data' => [],
        ], 201);
    }

    public function nestedFragmentUnshare(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-store');

        return $this->handleFragmentUnshare($this->fragmentRepository->find($fragmentOwnerModelId)->fragmentModel(), (int)$fragmentModelId);
    }

    public function fragmentDelete(Request $request, string $ownerKey, $ownerId, $fragmentModelId)
    {
        $this->guard('fragment-delete');

        return $this->handleFragmentDelete($this->ownerModel($ownerKey, $ownerId), (int)$fragmentModelId);
    }

    private function handleFragmentDelete(Model $ownerModel, int $fragmentModelId)
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        try {
            app(DetachFragment::class)->handle($ownerModel, $fragmentable->fragmentModel());
        } catch (FragmentAlreadyDetached $e) {
            return response()->json([
                'message' => 'fragment [' . $fragmentModelId . '] is already removed.',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'fragment [' . $fragmentModelId . '] removed',
            'data' => [],
        ]);
    }

    public function nestedFragmentDelete(Request $request, $fragmentOwnerModelId, $fragmentModelId)
    {
        $this->guard('fragment-delete');

        return $this->handleFragmentDelete($this->fragmentRepository->find((int)$fragmentOwnerModelId)->fragmentModel(), (int)$fragmentModelId);
    }

    public function fragmentStatus(Request $request, $fragmentId)
    {
        $this->guard('fragment-update');

        $fragmentable = $this->fragmentRepository->find((int)$fragmentId);

        $status = FragmentStatus::from($request->input('online_status'));

        if ($status == FragmentStatus::online) {
            app(PutFragmentOnline::class)->handle($fragmentable->fragmentModel()->id);
        }

        if ($status == FragmentStatus::offline) {
            app(PutFragmentOffline::class)->handle($fragmentable->fragmentModel()->id);
        }

        return response()->json([
            'message' => 'fragment online status updated',
            'data' => [],
        ]);
    }

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    /**
     * @return Factory|\Illuminate\Contracts\View\View
     */
    private function renderNestedFragmentCreate($owner, $order)
    {
        $fragmentable = $this->fragmentable();

        $forms = Forms::make($fragmentable->fields($fragmentable))
            ->fillModel($fragmentable->fragmentModel())
            ->fillFields($this, $fragmentable->fragmentModel())
            ->eachForm(function (Form $form) use ($fragmentable, $owner) {
                $form->action($this->route('nested-fragment-store', $owner))
                    ->refreshUrl('');
            });

        View::share('manager', $this);
        View::share('model', $fragmentable);
        View::share('resource', $this->resource);
        View::share('owner', $owner);
        View::share('forms', $forms);
        View::share('order', $order);

        return view('chief::manager.windows.fragments.create');
    }
}
