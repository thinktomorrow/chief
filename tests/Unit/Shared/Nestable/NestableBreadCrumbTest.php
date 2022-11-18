<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\PageNode;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestedNodeStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageRepository;

class NestableBreadCrumbTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelStub::class);
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

        $node = $this->findNode('fourth');

        $this->assertInstanceOf(PageNode::class, $node);
        $this->assertCount(2, $node->getBreadCrumbs());
        $this->assertEquals('label first nl', $node->getBreadCrumbs()[0]->getLabel('nl'));
        $this->assertEquals('label third nl', $node->getBreadCrumbs()[1]->getLabel('nl'));
    }

    public function test_it_can_get_localized_breadcrumb_label()
    {
        $this->defaultNestables();

        $node = $this->findNode('fourth');

        app()->setLocale('nl');
        $this->assertEquals('label fourth nl', $node->title);

        app()->setLocale('fr');
        $this->assertEquals('label fourth fr', $node->title);

        $this->assertEquals('label third nl > label fourth nl', $node->getBreadCrumbLabelWithoutRoot('nl'));
        $this->assertEquals('label first nl: label third nl > label fourth nl', $node->getBreadCrumbLabel('nl'));

        $this->assertEquals('label third fr > label fourth fr', $node->getBreadCrumbLabelWithoutRoot('fr'));
        $this->assertEquals('label first fr: label third fr > label fourth fr', $node->getBreadCrumbLabel('fr'));
    }

    public function test_it_can_get_parent_node()
    {
        $this->defaultNestables();
        $node = $this->findNode('fourth');

        $this->assertEquals('label third nl', $node->getParentNode()->getLabel());
    }

    public function test_it_can_get_all_children()
    {
        $this->defaultNestables();
        $node = $this->findNode('third');

        $this->assertEquals([
            $this->findNode('fourth')
        ], $node->getChildNodes()->all());
    }

    public function test_it_can_get_sibling_nodes()
    {
        $this->defaultNestables();
        $node = $this->findNode('second');
        $node2 = $this->findNode('third');

        $this->assertEquals([$node2], array_values($node->getSiblingNodes()->all()));
    }

    public function test_it_can_get_previous_and_next_sibling()
    {
        $this->defaultNestables();
        $node = $this->findNode('second');
        $node2 = $this->findNode('third');

        $this->assertEquals($node2, $node->getRightSiblingNode());
        $this->assertNull($node->getLeftSiblingNode());

        $this->assertEquals($node, $node2->getLeftSiblingNode());
        $this->assertNull($node2->getRightSiblingNode());
    }
}
