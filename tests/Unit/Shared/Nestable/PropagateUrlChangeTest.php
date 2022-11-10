<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageRepository;

class PropagateUrlChangeTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        NestableModelStub::migrateUp();
    }

    public function test_it_can_change_url_slug_when_parent_changes()
    {
        $this->prepareModels();

        $node = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fifth');

        // Change parent id to trigger url change
        $model = NestableModelStub::find('fifth');
        $model->parent_id = 'fourth';
        $model->save();

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => $node->getModel()::class,
            'modelId' => $node->getModel()->id,
            'links' => [
                'nl' => 'foobar-2',
            ],
        ]);

        $node = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fifth');
        $this->assertEquals('http://localhost/parent/foobar-2', $node->url());

        // Assert redirect is added
        $this->assertEquals('foobar', UrlRecord::findRecentRedirect(NestableModelStub::find('fifth'), 'nl')->slug);
    }

    public function test_it_changes_url_slug_when_parent_url_changes()
    {
        $this->prepareModels();

        // Change parent id to trigger url change
        $model = NestableModelStub::find('fifth');
        $model->parent_id = 'fourth';
        $model->save();

        $parentNode = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fourth');

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => $parentNode->getModel()::class,
            'modelId' => $parentNode->getModel()->id,
            'links' => [
                'nl' => 'parent-2',
            ],
        ]);

        $node = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fifth');
        $this->assertEquals('http://localhost/parent-2/foobar', $node->url());
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function prepareModels()
    {
        $this->defaultNestables();

        $node = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fifth');

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => $node->getModel()::class,
            'modelId'    => $node->getModel()->id,
            'links'      => [
                'nl' => 'foobar',
            ],
        ]);

        $parentNode = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fourth');

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => $parentNode->getModel()::class,
            'modelId'    => $parentNode->getModel()->id,
            'links'      => [
                'nl' => 'parent',
            ],
        ]);

        $parentNode = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fourth');
        $this->assertEquals('http://localhost/parent', $parentNode->url());

        $node = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fifth');
        $this->assertEquals('http://localhost/foobar', $node->url());
    }
}
