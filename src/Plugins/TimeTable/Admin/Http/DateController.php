<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TimeTableReadRepository;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateCreated;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateDeleted;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateUpdated;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\DateId;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DateModel;

class DateController extends Controller
{
    private FieldValidator $fieldValidator;
    private SaveFields $saveFields;
    private TimeTableReadRepository $timeTableReadRepository;

    public function __construct(TimeTableReadRepository $timeTableReadRepository, FieldValidator $fieldValidator, SaveFields $saveFields)
    {
        $this->fieldValidator = $fieldValidator;
        $this->saveFields = $saveFields;
        $this->timeTableReadRepository = $timeTableReadRepository;
    }

    public function index()
    {
        $timeTables = $this->timeTableReadRepository->getAll();

        return view('chief-weeks::weeks.index', [
            'weeks' => $weeks,
            'weekGroups' => $weekGroups,
        ]);
    }

    public function create()
    {
        $this->authorize('update-page');

        [, $fields] = $this->getModelAndFields();

        return view('chief-weeks::weeks.create', [
            'fields' => $fields,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new DateCreated(DateId::fromString($model->id)));

        return redirect()->route('chief.weeks.index')->with('messages.success', 'Tag is toegevoegd.');
    }

    public function edit($weekId)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($weekId);

        return view('chief-weeks::weeks.edit', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    public function update($weekId, Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($weekId);

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new DateUpdated(DateId::fromString($model->id)));

        return redirect()->route('chief.weeks.index')->with('messages.success', 'Tag is aangepast.');
    }

    public function delete($weekId)
    {
        $this->authorize('update-page');

        $model = app(DateModel::class)::find($weekId);

        // TODO: remove pivot entries as well

        $model->delete();

        event(new DateDeleted(DateId::fromString($model->id)));

        return redirect()->route('chief.weeks.index')->with('messages.success', 'Tag is verwijderd.');
    }

    private function getModelAndFields(?string $weekId = null): array
    {
        $model = app(DateModel::class);
        $model = $weekId ? $model::find($weekId): $model;
        dd(Fields::make($model->fields($model))->withoutNestedFields());
        $fields = Fields::makeWithoutFlatteningNestedFields($model->fields($model))->each(fn ($field) => $field->model($model));

        return [$model, $fields];
    }
}
