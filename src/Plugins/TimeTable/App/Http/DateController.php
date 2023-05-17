<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateCreated;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateDeleted;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateUpdated;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\DateId;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DateModel;

class DateController extends Controller
{
    private FieldValidator $fieldValidator;
    private SaveFields $saveFields;

    public function __construct(FieldValidator $fieldValidator, SaveFields $saveFields)
    {
        $this->fieldValidator = $fieldValidator;
        $this->saveFields = $saveFields;
    }

    public function create($timetable_id)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        // Enrich fields with the layout components
        $forms = Forms::make($model->fields($model))->fillModel($model)->getComponents();
        $fields = $forms[0]->getComponents();

        return view('chief-timetable::dates.create', [
            'fields' => $fields,
            'timetable_id' => $timetable_id,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        if($request->has('closed')) {
            $request = $request->merge(['slots' => [], 'closed' => true]);
        }

        $fields = $fields->remove('closed');

        $this->fieldValidator->handle($fields, $request->all());

        // TODO: create valid slots here (so we don't have any overlap)

        $model->date = $request->input('date');
        $model->slots = $request->input('slots');
        $model->content = $request->input('content');
        $model->save();

        // Connect to all timetables
        $model->timetables()->sync($request->input('timetables', []));

        event(new DateCreated(DateId::fromString($model->id)));

        if(count($timetableIds = $request->input('timetables', [])) > 0) {
            return redirect()->route('chief.timetables.edit', reset($timetableIds))->with('messages.success', 'Uitzondering is toegevoegd.');
        }

        return redirect()->route('chief.timetables.index')->with('messages.success', 'Dag is toegevoegd.');
    }

    public function edit($timetable_id, $dateId)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($dateId);

        // Enrich fields with the layout components
        $forms = Forms::make($model->fields($model))->fillModel($model)->getComponents();
        $fields = $forms[0]->getComponents();

        return view('chief-timetable::dates.edit', [
            'model' => $model,
            'fields' => $fields,
            'timetable_id' => $timetable_id,
        ]);
    }

    public function update($dateId, Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($dateId);

        if($request->has('closed')) {
            $request = $request->merge(['slots' => [], 'closed' => true]);
        }

        $fields = $fields->remove('closed');

        $this->fieldValidator->handle($fields, $request->all());

        // TODO: create valid slots here (so we don't have any overlap)

        $model->date = $request->input('date');
        $model->slots = $request->input('slots');
        $model->content = $request->input('content');
        $model->save();

        // Connect to all timetables
        $model->timetables()->sync($request->input('timetables', []));

        event(new DateUpdated(DateId::fromString($model->id)));

        if(count($timetableIds = $request->input('timetables', [])) > 0) {
            return redirect()->route('chief.timetables.edit', reset($timetableIds))->with('messages.success', 'Uitzondering is toegevoegd.');
        }

        return redirect()->route('chief.timetables.index')->with('messages.success', 'Uitzondering is aangepast.');
    }

    public function delete($dateId)
    {
        $this->authorize('update-page');

        $model = app(DateModel::class)::find($dateId);

        $model->delete();

        event(new DateDeleted(DateId::fromString($model->id)));

        return redirect()->route('chief.timetables.index')->with('messages.success', 'Uitzondering is verwijderd.');
    }

    private function getModelAndFields(?string $dateId = null): array
    {
        $model = app(DateModel::class);
        $model = $dateId ? $model::find($dateId): $model;

        $fields = Fields::makeWithoutFlatteningNestedFields($model->fields($model))->each(fn ($field) => $field->model($model));

        return [$model, $fields];
    }
}
