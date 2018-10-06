<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeActingAsChild;
use Thinktomorrow\Chief\Tests\TestCase;

class PresentRelationsTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
        ParentFake::migrate();
        ChildFake::migrate();
    }

    /** @test */
    public function a_child_can_be_presented_in_regards_to_its_parent()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $render = $child->presentForParent($parent, $child->relationWithParent($parent));
        $this->assertEquals('<div>child '.$child->id.' view for parent '.$parent->id.'</div>', $render);
    }

    /** @test */
    public function a_parent_has_a_convenience_method_for_presenting_all_children()
    {
        // TODO: ActingAsCollection should not be necessary for building up a children presentation...
        $this->app['config']->set('thinktomorrow.chief.collections', [
            'fakes' => ParentFake::class,
        ]);

        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $render = $parent->presentChildren();

        // They are not 2 separate array items since they are treated by the ParseChildrenForPresentation class as 'non-modules' which means they are set together as if they are a collection...
        // Not sure if this is totally expected behaviour...
        $this->assertEquals(collect([
            '<div>child '.$child->id.' view for parent '.$parent->id.'</div><div>child '.$child2->id.' view for parent '.$parent->id.'</div>'
        ]), $render);
    }

    /** @test */
    public function a_custom_model_can_be_presented_as_child()
    {
        // TODO: ActingAsCollection should not be necessary for building up a children presentation...
        $this->app['config']->set('thinktomorrow.chief.collections', [
            'fakes' => ParentFake::class,
        ]);

        ManagedModelFakeActingAsChild::migrateUp();

        $parent = ParentFake::create();
        $child = ManagedModelFakeActingAsChild::create();
        $child2 = ManagedModelFakeActingAsChild::create();
        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $render = $parent->presentChildren();
        $this->assertEquals(collect([
            'ManagedModelFakeActingAsChild presentation as childManagedModelFakeActingAsChild presentation as child'
        ]), $render);
    }

    /** @test */
    public function modules_are_presented_individually()
    {
        // TODO: ActingAsCollection should not be necessary for building up a children presentation...
        $this->app['config']->set('thinktomorrow.chief.collections', [
            'fakes' => ParentFake::class,
            'newsletters' => NewsletterModuleFake::class,
        ]);

        ManagedModelFakeActingAsChild::migrateUp();

        $parent = ParentFake::create();
        $child = NewsletterModuleFake::create(['collection' => 'newsletters', 'slug' => 'newsletter-1', 'content' => 'nieuwsbrief-1']);
        $child2 = NewsletterModuleFake::create(['collection' => 'newsletters', 'slug' => 'newsletter-2', 'content' => 'nieuwsbrief-2']);

        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $render = $parent->presentChildren();
        $this->assertEquals(collect([
            'nieuwsbrief-1',
            'nieuwsbrief-2',
        ]), $render);
    }
}
