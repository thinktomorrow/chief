<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait RepeatFieldAssistant
{
    public function routesRepeatFieldAssistant(): array
    {
        return [
            ManagedRoute::get('repeat-section', 'repeat-section/{fieldKey}/{id?}'),
        ];
    }

    public function repeatSection(Request $request, $fieldKey, $id = null)
    {
        if (! $request->filled('index')) {
            throw new \InvalidArgumentException('Required query value [index] missing.');
        }

        $prefix = $request->input('prefix');
        $index = $request->input('index');
        $locale = $request->input('locale');

        $model = $id ? $this->fieldsModel($id) : $this->managedModelClassInstance();

        $field = $this->resource->field($model, $fieldKey);

        $repeatSection = $field->getRepeatSection((int) $index, [], $locale, $prefix);

        // TODO: do this recursive because now only nested repeats are supported.
        foreach ($repeatSection as $nestedField) {
            if ($nestedField instanceof Field) {
                $nestedField->fill($this, $model instanceof Fragmentable ? $model->fragmentModel() : $model);
            }
        }

        return response()->json([
            'data' => $this->repeatSectionView($field, $repeatSection)->render(),
        ]);
    }

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    private function getParentRepeatField($model, $fieldKey): ?Repeat
    {
        foreach ($this->extractRepeatFieldsFrom($this->resource->fields($model)) as $parentRepeatField) {
            $match = $this->extractRepeatFieldsFrom($parentRepeatField->getComponents())
                ->filterBy(fn ($field) => $field->getKey() == $fieldKey)
                ->first()
            ;

            if ($match) {
                return $parentRepeatField;
            }
        }

        return null;
    }

    private function extractRepeatFieldsFrom(iterable $fields): Fields
    {
        return Fields::make($fields, fn ($field) => ! $field instanceof Repeat)
            ->filterBy(fn ($field) => $field instanceof Repeat)
        ;
    }

    private function repeatSectionView(Repeat $field, array $repeatSectionComponents): View
    {
        return view($field->getSectionView(), array_merge($field->data(), [
            'component' => $field,
            'components' => $repeatSectionComponents,
        ]));
    }
}
