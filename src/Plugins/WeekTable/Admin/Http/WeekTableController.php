<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableReadRepository;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Events\WeekTableCreated;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Events\WeekTableDeleted;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Events\WeekTableUpdated;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\WeekTableId;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\WeekTableModel;

class WeekTableController extends Controller
{
    private FieldValidator $fieldValidator;
    private SaveFields $saveFields;
    private WeekTableReadRepository $weekTableReadRepository;

    public function __construct(WeekTableReadRepository $weekTableReadRepository, FieldValidator $fieldValidator, SaveFields $saveFields)
    {
        $this->fieldValidator = $fieldValidator;
        $this->saveFields = $saveFields;
        $this->weekTableReadRepository = $weekTableReadRepository;
    }

    public function index()
    {
        return view('chief-weektable::weektables.index', [
            'weekTables' => $this->weekTableReadRepository->getAll(),
        ]);
    }

    public function create()
    {
        $this->authorize('update-page');

        [, $fields] = $this->getModelAndFields();

        return view('chief-weektable::weektables.create', [
            'fields' => $fields,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new WeekTableCreated(WeekTableId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag categorie is toegevoegd.');
    }

    public function edit($tagGroupId)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($tagGroupId);

        return view('chief-weektable::weektables.edit', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    public function update($tagGroupId, Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($tagGroupId);

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new WeekTableUpdated(WeekTableId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag categorie is aangepast.');
    }

    public function delete($tagGroupId)
    {
        $this->authorize('update-page');

        $model = app(WeekTableModel::class)::find($tagGroupId);

        // TODO: remove pivot entries as well

        $model->delete();

        event(new WeekTableDeleted(WeekTableId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag categorie is verwijderd.');
    }

    private function getModelAndFields(?string $tagGroupId = null): array
    {
        $model = app(WeekTableModel::class);
        $model = $tagGroupId ? $model::find($tagGroupId) : $model;

        $fields = Fields::make($model->fields($model))->each(fn ($field) => $field->model($model));

        return [$model, $fields];
    }
}
