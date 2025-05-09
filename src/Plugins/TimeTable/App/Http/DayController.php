<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DayModel;

class DayController extends Controller
{
    private FieldValidator $fieldValidator;

    private SaveFields $saveFields;

    public function __construct(FieldValidator $fieldValidator, SaveFields $saveFields)
    {
        $this->fieldValidator = $fieldValidator;
        $this->saveFields = $saveFields;
    }

    public function edit($id)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($id);

        // Enrich fields with the layout components
        $forms = Layout::make($model->fields($model))->model($model)->getComponents();
        $fields = $forms[0]->getComponents();

        return view('chief-timetable::days.edit', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    public function update($id, Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($id);

        if ($request->has('closed')) {
            $request = $request->merge(['slots' => [], 'closed' => true]);
        }

        $fields = $fields->remove('closed');

        $this->fieldValidator->handle($fields, $request->all());

        // TODO: create valid slots here (so we don't have any overlap)

        $model->slots = $request->input('slots');
        $model->content = $request->input('content');
        $model->save();

        return redirect()->route('chief.timetables.edit', $model->timetable_id)->with('messages.success', 'Dag is aangepast.');
    }

    private function getModelAndFields(?string $id = null): array
    {
        $model = app(DayModel::class);
        $model = $id ? $model::find($id) : $model;

        $fields = Fields::makeWithoutFlatteningNestedFields($model->fields($model))->each(fn ($field) => $field->model($model));

        return [$model, $fields];
    }
}
