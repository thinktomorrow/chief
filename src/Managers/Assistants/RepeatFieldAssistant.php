<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\ManagedModels\Actions\SortModels;

trait RepeatFieldAssistant
{
    public function routesRepeatFieldAssistant(): array
    {
        return [
            ManagedRoute::get('repeat-section', 'repeat-section/{id}/{fieldKey}'),
        ];
    }

    public function repeatSection(Request $request, $id, $fieldKey)
    {
        if(!$request->filled('index')) {
            throw new \InvalidArgumentException('Required query value [index] missing.');
        }

        $index = $request->input('index');
        $locale = $request->input('locale', null);
        $model = $this->managedModelClass()::findOrFail($id);

        $field = $model->field($fieldKey);
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