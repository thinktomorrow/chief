<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
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

        $index = $request->input('index');
        $locale = $request->input('locale', null);

        $model = $id ? $this->managedModelClass()::findOrFail($id) : $this->managedModelClassInstance();

        $field = $this->resource->field($model, $fieldKey);
        $repeatSection = $field->getRepeatSection((int) $index, [], $locale);

        return response()->json([
            'data' => $this->repeatSectionView($field, $repeatSection)->render(),
        ]);
    }

    private function repeatSectionView(Repeat $field, array $repeatSectionComponents): View
    {
        return view($field->getSectionView(), array_merge($field->data(), [
            'component' => $field,
            'components' => $repeatSectionComponents,
        ]));
    }

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;
}
