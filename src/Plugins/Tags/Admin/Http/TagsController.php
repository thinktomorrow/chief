<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Admin\Http;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Events\TagCreated;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Events\TagDeleted;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Events\TagUpdated;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagId;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;

class TagsController extends Controller
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

    public function index()
    {
        $tags = $this->tagReadRepository->getAll()->groupBy(fn (TagRead $tag) => $tag->getTagGroupId());
        $tagGroups = $this->tagReadRepository->getAllGroups();

        return view('chief-tags::tags.index', [
            'tags' => $tags,
            'tagGroups' => $tagGroups,
        ]);
    }

    public function create()
    {
        $this->authorize('update-page');

        [, $fields] = $this->getModelAndFields();

        return view('chief-tags::tags.create', [
            'fields' => $fields,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields();

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new TagCreated(TagId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag is toegevoegd.');
    }

    public function edit($tagId)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($tagId);

        return view('chief-tags::tags.edit', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    public function update($tagId, Request $request)
    {
        $this->authorize('update-page');

        [$model, $fields] = $this->getModelAndFields($tagId);

        $this->fieldValidator->handle($fields, $request->all());

        $this->saveFields->save($model, $fields, $request->all(), $request->allFiles());

        event(new TagUpdated(TagId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag is aangepast.');
    }

    public function delete($tagId)
    {
        $this->authorize('update-page');

        $model = app(TagModel::class)::find($tagId);

        // TODO: remove pivot entries as well

        $model->delete();

        event(new TagDeleted(TagId::fromString($model->id)));

        return redirect()->route('chief.tags.index')->with('messages.success', 'Tag is verwijderd.');
    }

    private function getModelAndFields(?string $tagId = null): array
    {
        $model = app(TagModel::class);
        $model = $tagId ? $model::find($tagId): $model;

        $fields = Fields::make($model->fields($model))->each(fn ($field) => $field->model($model));

        return [$model, $fields];
    }
}
