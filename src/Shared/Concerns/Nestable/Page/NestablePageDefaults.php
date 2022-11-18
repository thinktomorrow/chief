<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\PropagateUrlChange;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\SelectOptions;
use Thinktomorrow\Chief\Shared\Concerns\Sortable;

trait NestablePageDefaults
{
    use PageResourceDefault;
    use PageDefaults{
        baseUrlSegment as defaultBaseUrlSegment;
    }
    use Sortable;

    public static function bootNestablePageDefaults()
    {
        static::saved(function (self $model) {
            if ($model->exists && $model->isDirty('parent_id')) {
                if ($model->parent_id == $model->getKey()) {
                    throw new \DomainException('Cannot assign itself as parent. Model ['.$model->getKey().'] is set with its own id ['.$model->parent_id.'] as parent_id.');
                }

                $node = app(Registry::class)
                    ->findResourceByModel($model::class)
                    ->nestableRepository()
                    ->findNestableById($model->getKey())
                ;

                app(PropagateUrlChange::class)->handle($node);
            }
        });
    }

    public function isNestable(): bool
    {
        return true;
    }

    public function getInstanceAttributes(Request $request): array
    {
        return $this->getNestableInstanceAttributes($request);
    }

    public function baseUrlSegment(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if ($this->parent_id) {
            return $this->nestableRepository()
                ->findNestableById($this->getKey())
                ->getParentNode()
                ->getUrlSlug($locale) ?: '';
        }

        return $this->defaultBaseUrlSegment($locale);
    }

    protected function parentNodeSelect($model): iterable
    {
        $tree = $this->nestableRepository()->getTree();

        yield Form::make('nestable_parent_form')->position('aside')->items([
            MultiSelect::make('parent_id')
                ->label('Bovenliggende pagina')
                ->description('Onder welke pagina hoort deze thuis.')
//            ->grouped()
                ->options(fn () => app(SelectOptions::class)->getParentOptions($tree, $model)),
        ]);
    }

    private function viewData(): array
    {
        return array_merge([
            'model' => $this,
            'node' => $this->nestableRepository()->findNestableById($this->getKey()),
        ], $this->viewData);
    }

    /**
     * Allows to pass a predefined parent for the creation of a new nested model.
     */
    private function getNestableInstanceAttributes(Request $request): array
    {
        if ($request->has('parent_id')) {
            return ['parent_id' => $request->input('parent_id')];
        }

        return [];
    }
}
