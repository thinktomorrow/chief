<?php

namespace Thinktomorrow\Chief\Legacy\Fragments;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;
use Thinktomorrow\Chief\Media\Application\FileFieldHandler;
use Thinktomorrow\Chief\Media\Application\ImageFieldHandler;

trait ManagesFragments
{
    public function saveFragmentFields(FragmentField $fragmentField, Request $request)
    {
        $payload = $request->input($fragmentField->getDottedName(), []);
        $imagePayload = $request->input('images.' . $fragmentField->getDottedName(), []);

        // Compose Fragment instances for each payload entry
        $fragments = array_map(function ($fragmentPayload) use ($fragmentField) {
            return Fragment::fromRequestPayload($fragmentField->getKey(), $fragmentPayload); // (new Fragment($field->getKey(), $fragmentPayload));
        }, $payload);

        // remove all dead fragments
        $this->existingModel()->removeAllFragments($fragmentField->getKey(), array_map(function (Fragment $fragment) {
            return $fragment->hasModelId() ? $fragment->getModelId() : null;
        }, $fragments));

        // Save each fragment
        foreach ($fragments as $i => $fragment) {
            $modelId = $this->existingModel()->saveFragment($fragment, $i);

            // Attach any asset
            if (isset($imagePayload[$i])) {
                $fieldKey = key($imagePayload[$i]);
                $imageField = $this->fields()->keyed($fragmentField->getKey())->first()->getFields()->keyed($fieldKey)->first();

                $this->saveFragmentImageFields($imageField, $imagePayload[$i][$fieldKey], FragmentModel::find($modelId), $request);
            }
        }
    }

    private function saveFragmentImageFields(ImageField $field, array $values, FragmentModel $model, Request $request)
    {
        app(ImageFieldHandler::class)->handle($model, $field, $values, $request);
    }
}
