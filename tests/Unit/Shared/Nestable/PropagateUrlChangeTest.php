<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Illuminate\Contracts\Container\BindingResolutionException;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelResourceStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class PropagateUrlChangeTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelResourceStub::class);
        NestableModelResourceStub::migrateUp();
    }

    public function test_it_propagates_slug_change_of_parent()
    {
        $this->prepareModels();

        $this->changeParentModel('fifth', 'fourth');
        $this->assertEquals('http://localhost/parent/foobar', $this->findNode('fifth')->url());

        $this->changeSlug($this->findNode('fourth'), 'nl', 'parent-2');
        $this->assertEquals('http://localhost/parent-2/foobar', $this->findNode('fifth')->url());
    }

    public function test_it_does_not_propagate_when_slug_already_exists_on_another_model()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($this->findNode('fourth'), 'nl', 'existing-parent');
        $this->assertEquals('http://localhost/existing-parent/foobar', $this->findNode('fifth')->url());

        $this->changeSlug($this->findNode('first'), 'nl', 'foobar');
        $this->changeParentModel('fifth', null);

        // Because foobar already exists, we'll keeping the former slug
        $this->assertEquals('http://localhost/existing-parent/foobar', $this->findNode('fifth')->url());
    }

    public function test_it_changes_url_slug_of_all_children_when_parent_changes()
    {
        $this->prepareModels();

        $node = $this->findNode('fifth');
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($node, 'nl', 'foobar-2');

        $node = $this->findNode('fifth');
        $this->assertEquals('http://localhost/parent/foobar-2', $node->url());
    }

    public function test_after_changing_url_slug_a_redirect_is_added()
    {
        $this->prepareModels();

        $node = $this->findNode('fifth');
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($node, 'nl', 'foobar-2');

        $this->assertEquals('foobar', UrlRecord::findRecentRedirect(NestableModelStub::find('fifth'), 'nl')->slug);
    }

    public function test_it_changes_url_slug_of_all_children_when_parent_url_changes()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');

        $parentNode = $this->findNode('fourth');
        $this->changeSlug($parentNode, 'nl', 'parent-2');

        $node = $this->findNode('fifth');
        $this->assertEquals('http://localhost/parent-2/foobar', $node->url());
    }

    public function test_it_uses_current_parent_slug_for_url_slug_when_parent_selection_changes()
    {
        $this->prepareModels();
        $this->changeSlug($this->findNode('fourth'), 'nl', 'parent-2');
        $this->changeParentModel('fifth', 'fourth');

        $this->assertEquals('http://localhost/parent-2/foobar', $this->findNode('fifth')->url());
    }

    public function test_it_does_propagate_when_parent_is_removed()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($this->findNode('fourth'), 'nl', 'existing-parent');
        $this->assertEquals('http://localhost/existing-parent/foobar', $this->findNode('fifth')->url());

        $this->changeParentModel('fifth', null);
        $this->assertEquals('http://localhost/foobar', $this->findNode('fifth')->url());
    }

    public function test_it_does_propagate_when_parent_is_added()
    {
        $this->prepareModels();
        $this->assertEquals('http://localhost/foobar', $this->findNode('fifth')->url());

        $this->changeParentModel('fifth', 'fourth');
        $this->assertEquals('http://localhost/parent/foobar', $this->findNode('fifth')->url());
    }

    public function test_it_detects_when_slug_already_exists_on_the_model()
    {
        $this->prepareModels();
        $this->changeSlug($this->findNode('fifth'), 'nl', 'parent/foobar');

        $this->changeParentModel('fifth', 'fourth');
        $this->assertEquals('http://localhost/parent/foobar', $this->findNode('fifth')->url());

        // Change the parent slug now also gets replaced as expected
        $this->changeSlug($this->findNode('fourth'), 'nl', 'parent-2');
        $this->assertEquals('http://localhost/parent-2/foobar', $this->findNode('fifth')->url());
    }

    public function test_it_can_create_same_slug_on_child_model()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');

        $this->changeSlug($this->findNode('fifth'), 'nl', 'parent/foobar');
        $this->assertEquals('http://localhost/parent/parent/foobar', $this->findNode('fifth')->url());
    }

    public function test_child_slug_can_contain_slashes()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');

        $this->assertEquals('http://localhost/parent/foobar', $this->findNode('fifth')->url());

        $this->changeSlug($this->findNode('fifth'), 'nl', 'foo/bar');
        $this->assertEquals('http://localhost/parent/foo/bar', $this->findNode('fifth')->url());
    }

    public function test_parent_slug_can_contain_slashes()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($this->findNode('fourth'), 'nl', 'parent/changed');

        $this->assertEquals('http://localhost/parent/changed/foobar', $this->findNode('fifth')->url());
    }

    /**
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    private function prepareModels()
    {
        $this->defaultNestables(true);

        $node = $this->findNode('fifth');
        $this->changeSlug($node, 'nl', 'foobar');

        $parentNode = $this->findNode('fourth');
        $this->changeSlug($parentNode, 'nl', 'parent');

        $parentNode = $this->findNode('fourth');
        $this->assertEquals('http://localhost/parent', $parentNode->url());

        $node = $this->findNode('fifth');
        $this->assertEquals('http://localhost/foobar', $node->url());
    }
}
