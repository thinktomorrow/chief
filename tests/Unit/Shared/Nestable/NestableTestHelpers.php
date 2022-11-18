<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestedNodeStub;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageRepository;

trait NestableTestHelpers
{
    private function defaultNestables()
    {
        $modelFirst = new NestedNodeStub(NestableModelStub::create(['id' => 'first', 'order' => '0', 'title' => [
            'nl' => 'label first nl',
            'fr' => 'label first fr',
        ]]));

        $modelSecond = new NestedNodeStub(NestableModelStub::create(['id' => 'second', 'parent_id' => $modelFirst->id, 'order' => '1', 'title' => [
            'nl' => 'label second nl',
            'fr' => 'label second fr',
        ]]));

        $modelThird = new NestedNodeStub(NestableModelStub::create(['id' => 'third', 'parent_id' => $modelFirst->id, 'order' => '2', 'title' => [
            'nl' => 'label third nl',
            'fr' => 'label third fr',
        ]]));

        $modelFourth = new NestedNodeStub(NestableModelStub::create(['id' => 'fourth', 'parent_id' => $modelThird->id, 'order' => '3', 'title' => [
            'nl' => 'label fourth nl',
            'fr' => 'label fourth fr',
        ]]));

        $modelFifth = new NestedNodeStub(NestableModelStub::create(['id' => 'fifth', 'order' => '4', 'title' => [
            'nl' => 'label fifth nl',
            'fr' => 'label fifth fr',
        ]]));
    }

    private function findNode($modelId): NestedNode
    {
        return app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById($modelId);
    }

    private function changeParentModel($modelId, $parentId)
    {
        $model = NestableModelStub::find($modelId);
        $model->parent_id = $parentId;
        $model->save();
    }

    private function changeSlug($model, $locale, $slug)
    {
        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => $model::class,
            'modelId' => $model->id,
            'links' => [
                $locale => $slug,
            ],
        ]);
    }
}
