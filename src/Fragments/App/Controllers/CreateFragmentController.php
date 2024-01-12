<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentFactory;

class CreateFragmentController
{
    private FieldValidator $validator;
    private CreateFragment $createFragment;
    private AttachFragment $attachFragment;

    public function __construct(FieldValidator $validator, CreateFragment $createFragment, AttachFragment $attachFragment)
    {
        $this->validator = $validator;
        $this->createFragment = $createFragment;
        $this->attachFragment = $attachFragment;
    }

    public function create(string $contextId, string $fragmentKey, Request $request)
    {
        $context = ContextModel::find($contextId);
        $order = $request->input('order', 0);
        $fragment = app(FragmentFactory::class)->createObject($fragmentKey);

        $forms = Forms::make($fragment->fields($fragment))
            ->fillModel($fragment->fragmentModel())
            ->eachForm(function (Form $form) use ($fragment, $contextId, $fragmentKey) {
                $form->action(route('chief::fragments.store', [$contextId, $fragmentKey]))
                    ->refreshUrl('');
            });

        View::share('resource', $fragment);
        View::share('model', $fragment);
        View::share('owner', $context->getOwner());
        View::share('forms', $forms);
        View::share('order', $order);

        return view('chief-fragments::create');
    }

    public function store(string $contextId, string $fragmentKey, Request $request, ?string $redirectToRouteIfFragmentsOwner = null)
    {
        $fragment = app(FragmentFactory::class)->createObject($fragmentKey);

        $fields = Forms::make($fragment->fields($fragment))
//            ->fillLocalesIfEmpty((array)$request->input('admin_locales', []))
            ->fillModel($fragment->fragmentModel())
            ->getFields()
            ->notTagged(['edit', 'not-on-create']);

        $this->validator->handle($fields, $request->all());

        // Create Fragment db record
        $fragmentId = $this->createFragment->handle($fragmentKey, $request->all(), $request->allFiles());

        // Attach fragment to context
        $this->attachFragment->handle($contextId, $fragmentId, $request->input('order', 0));

        // If the fragment is a fragment owner ( = has nested fragments), we'll show the edit page of this fragment after creation
        // By default other fragments will return to the main edit page after being created.
        $redirectTo = ($fragment instanceof FragmentsOwner
            ? route($redirectToRouteIfFragmentsOwner ?: 'chief::fragments.edit', [$contextId, $fragmentId])
            : null);

        return response()->json([
            'message' => 'fragment created',
            'sidebar_redirect_to' => $redirectTo,
            'data' => [
                'fragmentmodel_id' => $fragmentId,
            ],
        ], 201);
    }
}
