<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentUpdated;

class EditFragmentController
{
    private FieldValidator $validator;
    private FragmentRepository $fragmentRepository;

    public function __construct(FieldValidator $validator, FragmentRepository $fragmentRepository)
    {
        $this->validator = $validator;
        $this->fragmentRepository = $fragmentRepository;
    }

    public function edit(string $contextId, string $fragmentId, Request $request)
    {
        $context = ContextModel::find($contextId);
        $fragment = $this->fragmentRepository->find($fragmentId);

        $forms = Forms::make($fragment->fields($fragment))
            ->fillModel($fragment->fragmentModel())
            ->eachForm(function (Form $form) use ($fragment, $contextId, $fragmentId) {
                $form->action(route('chief::fragments.update', [$contextId, $fragmentId]))
                    ->refreshUrl('');
            });

        View::share('resource', $fragment);
        View::share('model', $fragment);
        View::share('context', $context);
        View::share('owner', $context->getOwner());
        View::share('forms', $forms);

        return view('chief-fragments::edit');
    }

    public function update(string $contextId, string $fragmentId, Request $request)
    {
        $context = ContextModel::find($contextId);
        $fragment = $this->fragmentRepository->find($fragmentId);

        // Locales for save / validation -> which fields to validate.
        // Locales for visibility / associatedFragment -> where to show online

        // Locales are passed along the request as well to match the current model-fragment context.
//        if ($request->input('locales')) {
//            $fragmentable->fragmentModel()->setLocales($request->input('locales'));
//        }

        $forms = Forms::make($fragment->fields($fragment))
            ->fillModel($fragment->fragmentModel());

        $this->validator->handle($forms->getFields(), $request->all());

        // Save Fragment values
        app($fragment->getSaveFieldsClass())->save(
            $fragment->fragmentModel(),
            $forms->getFields(),
            $request->all(),
            $request->allFiles()
        );

        // Now set all locales for fields that require locales so that all values are saved on the fragment
//        $fragment->fragmentModel()->setLocales(ChiefLocaleConfig::getLocales());
//        $fields = $forms->fillModel($fragment->fragmentModel())->getFields();

//        app($this->resource->getSaveFieldsClass())->save($fragmentable->fragmentModel(), $fields, $request->all(), $request->allFiles());

        //        app(UpdateAssociatedFragment::class)->handle();

        event(new FragmentUpdated($fragment->getFragmentId()));

        return response()->json([
            'message' => 'fragment updated',
            'data' => [
                'fragmentmodel_id' => $fragment->getFragmentId(),
            ],
        ], 204);
    }
}
