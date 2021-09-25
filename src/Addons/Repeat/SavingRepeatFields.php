<?php

namespace Thinktomorrow\Chief\Addons\Repeat;

trait SavingRepeatFields
{
    public function saveRepeatField(RepeatField $field, $input, $files)
    {
        trap($input, data_get($input, $field->getDottedName()));

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
}
