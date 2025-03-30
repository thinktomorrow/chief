<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\TimeTableCreated;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\TimeTableDeleted;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\TimeTableUpdated;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DayModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;

class TimeTableController extends Controller
{
    private FieldValidator $fieldValidator;

    private SaveFields $saveFields;

    private TimeTableFactory $timeTableFactory;

    public function __construct(TimeTableFactory $timeTableFactory, FieldValidator $fieldValidator, SaveFields $saveFields)
    {
        $this->fieldValidator = $fieldValidator;
        $this->saveFields = $saveFields;
        $this->timeTableFactory = $timeTableFactory;
    }

    public function index()
    {
        return view('chief-timetable::timetables.index', [
            'timeTables' => app(TimeTableModel::class)->all()->map(function ($timeTable) {
                $timeTable->timeTable = $this->timeTableFactory->create($timeTable, app()->getLocale());

                return $timeTable;
            }),
        ]);
    }

    public function create()
    {
        $this->authorize('update-page');

        [, $fields] = $this->getModelAndFields();

        return view('chief-timetable::timetables.create', [
            'fields' => $fields->filterByNotTagged('not-on-create'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        $fields = $fields->filterByNotTagged('not-on-create');

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        app(DayModel::class)::createWeekWithDefaults($model);

        event(new TimeTableCreated(TimeTableId::fromString($model->id)));

        return redirect()->route('chief.timetables.index')->with('messages.success', 'Schema is toegevoegd.');
    }

    public function edit($id)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($id);

        return view('chief-timetable::timetables.edit', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    public function update($id, Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($id);

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new TimeTableUpdated(TimeTableId::fromString($model->id)));

        return redirect()->route('chief.timetables.index')->with('messages.success', 'Schema is aangepast.');
    }

    public function delete($id)
    {
        $this->authorize('update-page');

        $model = app(TimeTableModel::class)::find($id);

        // TODO: remove pivot entries as well

        $model->delete();

        event(new TimeTableDeleted(TimeTableId::fromString($model->id)));

        return redirect()->route('chief.timetables.index')->with('messages.success', 'Schema is verwijderd.');
    }

    private function getModelAndFields(?string $tagGroupId = null): array
    {
        $model = app(TimeTableModel::class);
        $model = $tagGroupId ? $model::find($tagGroupId) : $model;

        $fields = Fields::makeWithoutFlatteningNestedFields($model->fields($model))->each(fn ($field) => $field->model($model));

        return [$model, $fields];
    }
}
