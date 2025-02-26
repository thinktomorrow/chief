<?php

namespace Thinktomorrow\Chief\Fragments\Controllers;

use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachRootFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentFactory;

class CreateFragmentController
{
    private FieldValidator $validator;

    private CreateFragment $createFragment;

    private AttachRootFragment $attachFragment;

    public function __construct(FieldValidator $validator, CreateFragment $createFragment, AttachRootFragment $attachFragment)
    {
        $this->validator = $validator;
        $this->createFragment = $createFragment;
        $this->attachFragment = $attachFragment;
    }

    public function create(string $contextId, string $fragmentKey, ?string $parentId = null)
    {
        $context = ContextModel::find($contextId);
        $order = request()->input('order', 0);
        $fragment = app(FragmentFactory::class)->createObject($fragmentKey);

        $forms = Forms::make($fragment->fields($fragment))
//            ->fillModel($fragment->getFragmentModel())
            ->eachForm(function (Form $form) use ($contextId, $fragmentKey) {
                $form->action(route('chief::fragments.store', [$contextId, $fragmentKey]))
                    ->refreshUrl('');
            });

        View::share('resource', $fragment);
        View::share('model', $fragment);
        //        View::share('owner', $context->getOwner());
        View::share('forms', $forms);
        View::share('order', $order);

        return view('chief-fragments::create');
    }

    public function store(string $contextId, string $fragmentKey, ?string $parentId, ?string $redirectToRouteIfFragmentsOwner)
    {
        $request = request();
        $fragment = app(FragmentFactory::class)->createObject($fragmentKey);

        $fields = Forms::make($fragment->fields($fragment))
            ->getFields()
            ->notTagged(['edit', 'not-on-create']);

        $this->validator->handle($fields, $request->all());

        // Create Fragment db record
        $fragmentId = $this->createFragment->handle($fragmentKey, $request->all(), $request->allFiles());

        // Attach fragment to context
        $this->attachFragment->handle($contextId, $fragmentId, $parentId, $request->input('order', 0));

        // If the fragment is a fragment owner ( = has nested fragments), we'll show the edit page of this fragment after creation
        // By default other fragments will return to the main edit page after being created.
        $redirectTo = ($parentId
            ? route($redirectToRouteIfFragmentsOwner ?: 'chief::fragments.edit', [$contextId, $parentId])
            : null);

        return response()->json([
            'message' => 'fragment created',
            'sidebar_redirect_to' => $redirectTo,
            'data' => [
                'fragmentModelId' => $fragmentId,
                'parentId' => $parentId,
                'contextId' => $contextId,
            ],
        ], 201);
    }
}
