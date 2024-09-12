<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelResourceStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

class NestableModelTest extends ChiefTestCase
{
    use NestableTestHelpers;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelResourceStub::class);
        NestableModelResourceStub::migrateUp();

        $this->defaultNestables();
    }

    public function test_it_can_get_parent_page()
    {
        $node = $this->findNode('second');

        $this->assertEquals($this->findNode('first')->getAttributes(), $node->getParent()->getAttributes());
    }

    public function test_a_root_has_no_parent_page()
    {
        $node = $this->findNode('first');

        $this->assertNull($node->getParent());
    }

    public function test_it_can_get_ancestors()
    {
        $node = $this->findNode('fourth');
        $ancestors = $node->getAncestors();

        $this->assertCount(2, $ancestors);

        $this->assertInstanceOf($node::class, $ancestors[0]);
        $this->assertEquals('first', $ancestors[0]->id);
        $this->assertEquals('third', $ancestors[1]->id);
    }

    public function test_it_can_get_breadcrumbs()
    {
        $node = $this->findNode('fourth');

        $this->assertCount(2, $node->getAncestors());
        $this->assertEquals('label first nl', $node->getAncestors()[0]->title);
        $this->assertEquals('label third nl', $node->getAncestors()[1]->title);
    }

    public function test_it_can_get_children()
    {
        $node = $this->findNode('first');

        $model = $node;

        $this->assertCount(2, $model->getChildren());
        $this->assertEquals($this->findNode('second')->getAttributes(), $model->getChildren()[0]->getAttributes());
        $this->assertEquals($this->findNode('third')->getAttributes(), $model->getChildren()[1]->getAttributes());
    }

    public function test_it_can_get_descendants()
    {
        $node = $this->findNode('first');

        $model = $node;

        $this->assertInstanceOf(NestableTree::class, $model->getDescendants());
        $this->assertEquals(3, $model->getDescendants()->total());

        $this->assertEquals($this->findNode('second')->getAttributes(), $model->getDescendants()[0]->getAttributes());
        $this->assertEquals($this->findNode('third')->getAttributes(), $model->getDescendants()[1]->getAttributes());
        $this->assertEquals($this->findNode('fourth')->getAttributes(), $model->getDescendants()[1]->getChildNodes()[0]->getAttributes());
    }

    public function test_it_can_get_siblings()
    {
        $node = $this->findNode('second');
        $model = $node;

        $this->assertCount(1, $model->getSiblings());
        $this->assertEquals($this->findNode('third')->getAttributes(), $model->getSiblings()[0]->getAttributes());
    }

    public function test_it_can_get_no_siblings_when_there_are_none()
    {
        $node = $this->findNode('fourth');
        $model = $node;

        $this->assertCount(0, $model->getSiblings());
    }

    public function test_it_can_get_siblings_of_root()
    {
        $node = $this->findNode('first');
        $model = $node;

        $this->assertCount(1, $model->getSiblings());
        $this->assertEquals($this->findNode('fifth')->getAttributes(), $model->getSiblings()[0]->getAttributes());
    }

    public function test_a_non_existing_model_has_no_descendants()
    {
        $model = new NestableModelStub();

        $this->assertEmpty($model->getDescendantIds());
        $this->assertInstanceOf(NestableTree::class, $model->getDescendants());
        $this->assertEquals(0, $model->getDescendants()->total());
    }

    public function test_a_non_existing_model_has_no_ancestors()
    {
        $model = new NestableModelStub();

        $this->assertCount(0, $model->getAncestors());
    }

    public function test_a_non_existing_model_has_no_siblings()
    {
        $model = new NestableModelStub();

        $this->assertCount(0, $model->getSiblings());
    }
}
