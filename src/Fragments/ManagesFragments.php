<?php

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\FileField;
use Thinktomorrow\Chief\Fields\Types\ImageField;
use Thinktomorrow\Chief\Media\Application\FileFieldHandler;
use Thinktomorrow\Chief\Media\Application\ImageFieldHandler;

trait ManagesFragments
{
    public function saveFragmentFields(FragmentField $fragmentField, Request $request)
    {
        $payload = $request->input($fragmentField->getDottedName(), []);
        $imagePayload = $request->input('images.'.$fragmentField->getDottedName(), []);

        // Merge with any asset input
//        foreach($imagePayload as $k => $imagePayloadEntry) {
//            if(isset($payload[$k])) {
//                $payload[$k] = array_merge($payload[$k], $imagePayloadEntry);
//            } else {
//                $payload[$k] = $imagePayloadEntry;
//            }
//        }

//        trap($payload);

        // TEST

        $fragments = array_map(function($fragmentPayload) use($fragmentField){
            return Fragment::fromRequestPayload($fragmentField->getKey(), $fragmentPayload); // (new Fragment($field->getKey(), $fragmentPayload));
        }, $payload);

        // remove all dead fragments
        $this->existingModel()->removeAllFragments($fragmentField->getKey(), array_map(function (Fragment $fragment) {
            return $fragment->hasModelId() ? $fragment->getModelId() : null;
        }, $fragments));

        // Save each fragment
        foreach($fragments as $i => $fragment) {
            $modelId = $this->existingModel()->saveFragment($fragment, $i);

            // Attach any asset
            if(isset($imagePayload[$i])) {
                $fieldKey = key($imagePayload[$i]);
                $imageField = $this->fields()->keyed($fragmentField->getKey())->first()->getFields()->keyed($fieldKey)->first();

                app(ImageFieldHandler::class)->handle(FragmentModel::find($modelId), $imageField, $imagePayload[$i][$fieldKey] , $request);
            }
        }

    }

    private function saveFragmentFileFields(FileField $field, Request $request)
    {
        app(FileFieldHandler::class)->handle($this->model, $field, $request);
    }

    private function saveFragmentImageFields(ImageField $field, Request $request)
    {
        app(ImageFieldHandler::class)->handle($this->model, $field, $request);
    }
}
