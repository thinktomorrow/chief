<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableReadRepository;
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
    private TimeTableReadRepository $timeTableReadRepository;

    public function __construct(TimeTableReadRepository $timeTableReadRepository, FieldValidator $fieldValidator, SaveFields $saveFields)
    {
        $this->fieldValidator = $fieldValidator;
        $this->saveFields = $saveFields;
        $this->timeTableReadRepository = $timeTableReadRepository;
    }

    public function index()
    {
        return view('chief-timetable::timetables.index', [
            'timeTables' => $this->timeTableReadRepository->getAll(),
        ]);
    }

    public function create()
    {
        $this->authorize('update-page');

        [, $fields] = $this->getModelAndFields();

        return view('chief-timetable::timetables.create', [
            'fields' => $fields->notTagged('not-on-create'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        $fields = $fields->notTagged('not-on-create');

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        $this->createDays($model);

        event(new TimeTableCreated(TimeTableId::fromString($model->id)));

        return redirect()->route('chief.timetables.index')->with('messages.success', 'Schema is toegevoegd.');
    }

    private function createDays($model)
    {
        $existingDays = TimeTableModel::exists() ? TimeTableModel::first()->days : collect();

        foreach(range(1,7) as $weekDay) {

            $day = $existingDays->first(fn($day) => $day->weekday == $weekDay);
            $slots = $day ? $day->slots : $this->defaultSlots($weekDay);

            $model->days()->create([
                'weekday' => $weekDay,
                'slots' => $slots,
            ]);
        }
    }

    private function defaultSlots($weekDay): array
    {
        return match($weekDay) {
            '6','7' => [],
            default => [
                ['from' => '08:30', 'until' => '12:00'],
                ['from' => '13:00', 'until' => '17:00']
            ]
        };
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
