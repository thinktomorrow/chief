<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\PageNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestedNodeStub;

class NestableModelTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelStub::class);
        NestableModelStub::migrateUp();

        $this->defaultNestables();
    }

    public function test_it_can_get_parent_page()
    {
        $node = $this->findNode('second');

        $this->assertEquals($this->findNode('first')->getModel()->getAttributes(), $node->getModel()->getParent()->getAttributes());
    }

    public function test_a_root_has_no_parent_page()
    {
        $node = $this->findNode('first');

        $this->assertNull($node->getModel()->getParent());
    }

    public function test_it_can_get_ancestors()
    {
        $node = $this->findNode('fourth');
        $ancestors = $node->getModel()->getAncestors();

        $this->assertCount(2, $ancestors);

        $this->assertInstanceOf($node->getModel()::class, $ancestors[0]);
        $this->assertEquals('first', $ancestors[0]->id);
        $this->assertEquals('third', $ancestors[1]->id);
    }

    public function test_it_can_get_breadcrumbs()
    {
        $node = $this->findNode('fourth');

        $this->assertCount(2, $node->getModel()->getAncestors());
        $this->assertEquals('label first nl', $node->getModel()->getAncestors()[0]->title);
        $this->assertEquals('label third nl', $node->getModel()->getAncestors()[1]->title);
    }

    public function test_it_can_get_children()
    {
        $node = $this->findNode('first');

        $model = $node->getModel();

        $this->assertCount(2, $model->getChildren());
        $this->assertEquals($this->findNode('second')->getModel()->getAttributes(), $model->getChildren()[0]->getAttributes());
        $this->assertEquals($this->findNode('third')->getModel()->getAttributes(), $model->getChildren()[1]->getAttributes());
    }

    public function test_it_can_get_descendants()
    {
        $node = $this->findNode('first');

        $model = $node->getModel();

        $this->assertInstanceOf(NestedTree::class, $model->getDescendants());
        $this->assertEquals(3, $model->getDescendants()->total());

        $this->assertEquals($this->findNode('second')->getModel()->getAttributes(), $model->getDescendants()[0]->getModel()->getAttributes());
        $this->assertEquals($this->findNode('third')->getModel()->getAttributes(), $model->getDescendants()[1]->getModel()->getAttributes());
        $this->assertEquals($this->findNode('fourth')->getModel()->getAttributes(), $model->getDescendants()[1]->getChildNodes()[0]->getModel()->getAttributes());
    }

    public function test_it_can_get_siblings()
    {
        $node = $this->findNode('second');
        $model = $node->getModel();

        $this->assertCount(1, $model->getSiblings());
        $this->assertEquals($this->findNode('third')->getModel()->getAttributes(), $model->getSiblings()[0]->getAttributes());
    }

    public function test_it_can_get_no_siblings_when_there_are_none()
    {
        $node = $this->findNode('fourth');
        $model = $node->getModel();

        $this->assertCount(0, $model->getSiblings());
    }

    public function test_it_can_get_siblings_of_root()
    {
        $node = $this->findNode('first');
        $model = $node->getModel();

        $this->assertCount(1, $model->getSiblings());
        $this->assertEquals($this->findNode('fifth')->getModel()->getAttributes(), $model->getSiblings()[0]->getAttributes());
    }
}
