<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestablePageStub;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\PageNode;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestedNodeStub;

class NestableBreadCrumbTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        NestableModelStub::migrateUp();
    }

    public function test_breadcrumbs_by_default_are_empty()
    {
        $model = new NestedNodeStub(NestableModelStub::create(['id' => 'xxx']));
        $this->assertEmpty($model->getBreadCrumbs());
    }

    public function test_it_can_get_breadcrumbs()
    {
        $this->defaultNestables();

        $model = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fourth');

        $this->assertInstanceOf(PageNode::class, $model);
        $this->assertCount(2, $model->getBreadCrumbs());
        $this->assertEquals('label first nl', $model->getBreadCrumbs()[0]->getLabel('nl'));
        $this->assertEquals('label third nl', $model->getBreadCrumbs()[1]->getLabel('nl'));
    }

    public function test_it_can_get_localized_breadcrumb_label()
    {
        $this->defaultNestables();

        $model = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class])->findNestableById('fourth');

        app()->setLocale('nl');
        $this->assertEquals('label fourth nl', $model->title);

        app()->setLocale('fr');
        $this->assertEquals('label fourth fr', $model->title);

        $this->assertEquals('label third nl > label fourth nl', $model->getBreadCrumbLabelWithoutRoot('nl'));
        $this->assertEquals('label first nl: label third nl > label fourth nl', $model->getBreadCrumbLabel('nl'));

        $this->assertEquals('label third fr > label fourth fr', $model->getBreadCrumbLabelWithoutRoot('fr'));
        $this->assertEquals('label first fr: label third fr > label fourth fr', $model->getBreadCrumbLabel('fr'));
    }
}
