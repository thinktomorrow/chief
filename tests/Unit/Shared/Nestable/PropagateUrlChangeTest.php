<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Illuminate\Contracts\Container\BindingResolutionException;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

class PropagateUrlChangeTest extends ChiefTestCase
{
    use NestableTestHelpers;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelStub::class);
        NestableModelStub::migrateUp();
    }

    public function test_it_can_change_url_slug_when_parent_changes()
    {
        $this->prepareModels();

        $node = $this->findNode('fifth');

        $this->changeParentModel('fifth', 'fourth');

        $this->changeSlug($node->getModel(), 'nl', 'foobar-2');

        $node = $this->findNode('fifth');
        $this->assertEquals('http://localhost/parent/foobar-2', $node->getModel()->url());

        // Assert redirect is added
        $this->assertEquals('foobar', UrlRecord::findRecentRedirect(NestableModelStub::find('fifth'), 'nl')->slug);
    }

    /**
     * @return mixed
     * @throws BindingResolutionException
     */
    private function prepareModels()
    {
        $this->defaultNestables(true);

        $node = $this->findNode('fifth');
        $this->changeSlug($node->getModel(), 'nl', 'foobar');

        $parentNode = $this->findNode('fourth');
        $this->changeSlug($parentNode->getModel(), 'nl', 'parent');

        $parentNode = $this->findNode('fourth');
        $this->assertEquals('http://localhost/parent', $parentNode->getModel()->url());

        $node = $this->findNode('fifth');
        $this->assertEquals('http://localhost/foobar', $node->getModel()->url());
    }

    public function test_it_changes_url_slug_when_parent_url_changes()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');

        $parentNode = $this->findNode('fourth');
        $this->changeSlug($parentNode->getModel(), 'nl', 'parent-2');

        $node = $this->findNode('fifth');
        $this->assertEquals('http://localhost/parent-2/foobar', $node->getModel()->url());
    }

    public function test_it_can_change_url_when_parent_selection_changes()
    {
        $this->prepareModels();
        $this->changeSlug($this->findNode('fourth')->getModel(), 'nl', 'parent-2');
        $this->changeParentModel('fifth', 'fourth');

        $this->assertEquals('http://localhost/parent-2/foobar', $this->findNode('fifth')->getModel()->url());
    }

    public function test_it_does_propagate_when_parent_is_removed()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($this->findNode('fourth')->getModel(), 'nl', 'existing-parent');
        $this->assertEquals('http://localhost/existing-parent/foobar', $this->findNode('fifth')->getModel()->url());

        $this->changeParentModel('fifth', null);
        $this->assertEquals('http://localhost/foobar', $this->findNode('fifth')->getModel()->url());
    }

    public function test_it_does_propagate_when_slug_already_exists_on_the_same_model()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($this->findNode('fourth')->getModel(), 'nl', 'parent-2');

        $this->assertEquals('http://localhost/parent-2/foobar', $this->findNode('fifth')->getModel()->url());
    }

    public function test_it_does_not_propagate_when_slug_already_exists_on_another_model()
    {
        $this->prepareModels();
        $this->changeParentModel('fifth', 'fourth');
        $this->changeSlug($this->findNode('fourth')->getModel(), 'nl', 'existing-parent');
        $this->assertEquals('http://localhost/existing-parent/foobar', $this->findNode('fifth')->getModel()->url());

        $this->changeSlug($this->findNode('first')->getModel(), 'nl', 'foobar');
        $this->changeParentModel('fifth', null);

        // Because foobar already exists, we'll keeping the former slug
        $this->assertEquals('http://localhost/existing-parent/foobar', $this->findNode('fifth')->getModel()->url());
    }
}
