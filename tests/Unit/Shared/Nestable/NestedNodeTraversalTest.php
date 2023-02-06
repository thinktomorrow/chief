<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\PageNode;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

class NestedNodeTraversalTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelStub::class);
        NestableModelStub::migrateUp();
    }

    public function test_it_can_get_parent_node()
    {
        $this->defaultNestables();
        $node = $this->findNode('fourth');

        $this->assertEquals('label third nl', $node->getParentNode()->getModel()->title);
    }

    public function test_it_can_get_all_children()
    {
        $this->defaultNestables();
        $node = $this->findNode('third');

        $this->assertEquals([
            $this->findNode('fourth'),
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

    public function test_it_uses_select_label_as_breadcrumb_label()
    {
        $this->defaultNestables();

        $node = $this->findNode('fourth');

        app()->setLocale('nl');
        $this->assertEquals('label third nl [offline] > label fourth nl [offline]', $node->getBreadCrumbLabelWithoutRoot());
    }

    public function test_it_can_get_localized_breadcrumb_label()
    {
        $this->defaultNestables(true);

        $node = $this->findNode('fourth');

        app()->setLocale('nl');
        $this->assertEquals('label fourth nl', $node->getModel()->title);
        $this->assertEquals('label third nl > label fourth nl', $node->getBreadCrumbLabelWithoutRoot());
        $this->assertEquals('label first nl: label third nl > label fourth nl', $node->getBreadCrumbLabel());

        app()->setLocale('fr');
        $this->assertEquals('label fourth fr', $node->getModel()->title);
        $this->assertEquals('label third fr > label fourth fr', $node->getBreadCrumbLabelWithoutRoot());
        $this->assertEquals('label first fr: label third fr > label fourth fr', $node->getBreadCrumbLabel());
    }
}
