<?php

namespace Thinktomorrow\Chief\Tests\Unit\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Collections\CollectionDetails;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Common\FlatReferences\Types\CollectionFlatReference;
use Thinktomorrow\Chief\Tests\Fakes\ActsAsCollectionFake;
use Thinktomorrow\Chief\Tests\Fakes\ActsAsCollectionFakeModel;
use Thinktomorrow\Chief\Tests\TestCase;

class CollectionsTest extends TestCase
{
    /** @test */
    public function it_can_create_a_collection_from_flat_references()
    {
        $first = new ActsAsCollectionFake(1, 'first', 'group');
        $second = new ActsAsCollectionFake(2, 'second', 'group');

        $flatReferences = FlatReferenceCollection::make([$first, $second])->toFlatReferences();

        $this->assertInstanceOf(Collection::class, $flatReferences);
        $this->assertEquals($first->flatReference()->get(), $flatReferences[0]);
        $this->assertEquals($second->flatReference()->get(), $flatReferences[1]);
    }

    /** @test */
    public function it_can_provide_collection_details()
    {
        $first = new ActsAsCollectionFake(1, 'first', 'group');

        $details = $first->collectionDetails();

        $this->assertInstanceOf(CollectionDetails::class, $details);
        $this->assertEquals(1, $details->key);
        $this->assertEquals(ActsAsCollectionFake::class, $details->className);
        $this->assertEquals('first', $details->singular);
        $this->assertEquals('first', $details->plural);
        $this->assertEquals('first', $details->internal_label);
    }

    /** @test */
    public function it_can_create_collection_id_from_string()
    {
        $this->setUpCollectionFakeWorld();

        $acts_as_collection = ActsAsCollectionFakeModel::create(['label' => 'new label']);

        $this->assertInstanceOf(CollectionFlatReference::class, $acts_as_collection->flatReference());

        $id = FlatReferenceFactory::fromString(ActsAsCollectionFakeModel::class.'@'.$acts_as_collection->id);
        $this->assertTrue($id->equals($acts_as_collection->flatReference()));
    }

    /** @test */
    public function it_can_create_instance_from_collection_id()
    {
        $this->setUpCollectionFakeWorld();

        $first = ActsAsCollectionFakeModel::create(['collection' => 'has_collection_fakes']);
        $instance = $first->flatReference()->instance();

        $this->assertInstanceOf(ActsAsCollectionFakeModel::class, $instance);
        $this->assertEquals($first->id, $instance->id);
    }

    /** @test */
    public function it_can_instantiate_multiple_collection_ids()
    {
        $this->setUpCollectionFakeWorld();

        $first = ActsAsCollectionFakeModel::create();
        $second = ActsAsCollectionFakeModel::create();

        $instances = FlatReferenceCollection::fromFlatReferences(collect([
            $first->flatReference()->get(),
            $second->flatReference()->get()
        ]));

        $this->assertCount(2, $instances);

        foreach ($instances as $instance) {
            $this->assertInstanceOf(Model::class, $instance);
        }
    }

    protected function setUpCollectionFakeWorld()
    {
        $this->setUpDatabase();
        ActsAsCollectionFakeModel::migrateUp();

        $this->app['config']->set('thinktomorrow.chief.collections.pages', [
            'has_collection_fakes' => ActsAsCollectionFakeModel::class,
        ]);
    }
}
