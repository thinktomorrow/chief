<?php

namespace Thinktomorrow\Chief\Admin\Tags\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Tags\Events\TagGroupCreated;
use Thinktomorrow\Chief\Admin\Tags\Events\TagGroupDeleted;
use Thinktomorrow\Chief\Admin\Tags\Events\TagGroupUpdated;
use Thinktomorrow\Chief\Admin\Tags\Read\TagReadRepository;
use Thinktomorrow\Chief\Admin\Tags\TagGroupId;
use Thinktomorrow\Chief\Admin\Tags\TagGroupModel;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\SaveFields;

class TagGroupsController extends Controller
{
    private TagReadRepository $tagReadRepository;
    private FieldValidator $fieldValidator;
    private SaveFields $saveFields;

    public function __construct(TagReadRepository $tagReadRepository, FieldValidator $fieldValidator, SaveFields $saveFields)
    {
        $this->tagReadRepository = $tagReadRepository;
        $this->fieldValidator = $fieldValidator;
        $this->saveFields = $saveFields;
    }

    public function create()
    {
        $this->authorize('update-page');

        [, $fields] = $this->getModelAndFields();

        return view('chief::admin.tags.taggroups.create', [
            'fields' => $fields,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new TagGroupCreated(TagGroupId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag categorie is toegevoegd.');
    }

    public function edit($tagGroupId)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($tagGroupId);

        return view('chief::admin.tags.taggroups.edit', [
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

        event(new TagGroupUpdated(TagGroupId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag categorie is aangepast.');
    }

    public function delete($tagGroupId)
    {
        $this->authorize('update-page');

        $model = app(TagGroupModel::class)::find($tagGroupId);

        // TODO: remove pivot entries as well

        $model->delete();

        event(new TagGroupDeleted(TagGroupId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag categorie is verwijderd.');
    }

    private function getModelAndFields(?string $tagGroupId = null): array
    {
        $model = app(TagGroupModel::class);
        $model = $tagGroupId ? $model::find($tagGroupId) : $model;

        $fields = Fields::make($model->fields($model))->each(fn($field) => $field->model($model));

        return [$model, $fields];
    }
}
