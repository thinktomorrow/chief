<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Assistants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Application\DeleteModel;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

// TODO: these routes are just as transition from the old management way. At the end this assistant should become obsolete since there are no substantial differences between management of 'pages' and 'modules'
trait ModuleCrudAssistant
{
    abstract protected function fieldValidator(): FieldValidator;
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;
    abstract protected function guard(string $action);

    public static function routesModuleCrudAssistant(): array
    {
        return [
            ManagedRoute::get('create', 'module/{owner_type}/{owner_id}/create'),
            ManagedRoute::get('create-shared', 'module/create'), // TODO: Should be removed...
            ManagedRoute::post('store', 'module'),
        ];
    }

    public function routeModuleCrudAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! in_array($action, ['create','store'])) {
            return null;
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function create()
    {
        $modelClass = $this->managedModelClass();

        return view('chief::back.managers.create', [
            'manager' => $this,
            'fields' => (new $modelClass())->fields()->tagged('create'),
        ]);
    }

    public function store(Request $request, string $owner_type, $owner_id)
    {
        $this->guard('store');

        $modelClass = $this->managedModelClass();

        /** @var ManagedModel $model */
        $model = new $modelClass();

        $this->fieldValidator()->handle($model->fields()->tagged('create'), $request->all());

        $model->saveFields($model->fields()->tagged('create'), $request->all(), $request->allFiles());

        return redirect()->to($this->route('edit', $model))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminConfig()->getPageTitle() . '" is toegevoegd');
    }

    public function edit($id)
    {
        $this->guard('edit');

        $modelClass = $this->managedModelClass();

        /** @var Model $model */
        $model = $modelClass::findOrFail($id);

        return view('chief::back.managers.edit', [
            'manager' => $this,
            'model' => $model,
            'fields' => $model->fields()->map(function (Field $field) use ($model) {
                // TODO refactor so render method of field takes model and managerViewModel as arguments.
                return $field->model($model);
            }),
        ]);
    }

    public function update($id, Request $request)
    {
        $this->guard('update');

        /** @var ManagedModel $model */
        $model = $this->managedModelClass()::findOrFail($id);

        $this->fieldValidator()->handle($model->fields(), $request->all());

        $model->saveFields($model->fields(), $request->all(), $request->allFiles());

        return redirect()->to($this->route('edit', $model))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminConfig()->getPageTitle() . '" is aangepast');
    }

    public function delete($id, Request $request)
    {
        $this->guard('delete');

        $model = $this->managedModelClass()::findOrFail($id);

        if ($request->get('deleteconfirmation') !== 'DELETE') {
            return redirect()->back()->with('messages.warning', $model->adminConfig()->getPageTitle() . ' is niet verwijderd.');
        }

        app(DeleteModel::class)->handle($model);

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminConfig()->getPageTitle() . '" is verwijderd.');
    }
}
