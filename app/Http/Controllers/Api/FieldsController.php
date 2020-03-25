<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Fields\FieldReference;
use Thinktomorrow\Chief\Fields\FieldRepository;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Fields\Types\ImageField;
use Thinktomorrow\Chief\Fields\Types\SelectField;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Urls\UrlHelper;

class FieldsController extends Controller
{
    /** @var FieldRepository */
    private $fieldRepository;

    public function __construct(FieldRepository $fieldRepository)
    {
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * Return the isolated field view for integration in the frontend
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $fieldReference = new FieldReference($request->input('managerKey'), $request->input('fieldKey'),$request->input('fragmentKey'));

        $field = $this->fieldRepository->find($fieldReference);

        // TODO: model injection??????

        $html = $field->render();
        // 1. Detect which field we are looking for...
        // Idea: field identifier: managerKey.fragmentKey.fieldKey. We could use a lookup repo to then find the corresponding field

        // 2. Render the partial without executing the vue/js

        // TEST
//        $html = SelectField::make('test')->multiple()->options(['one' => 'one', '2' => 'twowooohooo!'])->render();
//
//        $html .= ImageField::make('testje')->render();
//        $html .= HtmlField::make('testjesdfsdf')->value('<strong>DFMDKLFJLSMDKFJSLMKDJFLMDKSJFDf sdf dsmjf sqdmlkf</strong>')->render();


        return response()->json([
            'data' => $html,
        ]);
    }
}
