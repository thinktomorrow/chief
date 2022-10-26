<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\SelectOptions;

trait NestableDefaults
{
    protected function setNestedNodeAsModelInView()
    {
        $this->setViewData(['model' => $this->nestableRepository()->findNestableById($this->getKey())]);
    }

    /**
     * Allows to pass a predefined parent
     * for the creation of a new nested model.
     *
     * @param Request $request
     * @return array
     */
    public function getNestableInstanceAttributes(Request $request): array
    {
        if ($request->has('parent_id')) {
            return ['parent_id' => $request->input('parent_id')];
        }

        return [];
    }

    protected function parentNodeSelect($model): iterable
    {
        $tree = $this->nestableRepository()->getTree();

        yield MultiSelect::make('parent_id')
            ->label('Bovenliggende pagina')
            ->description('Onder welke pagina hoort deze thuis.')
//            ->grouped()
            ->options(fn () => app(SelectOptions::class)->getParentOptions($tree, $model));
    }
}
