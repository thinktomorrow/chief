<?php

namespace Thinktomorrow\Chief\Tests\Unit\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Collections\CollectionId;
use Thinktomorrow\Chief\Common\Collections\CollectionItems;
use Thinktomorrow\Chief\Tests\Fakes\HasCollectionIdFake;
use Thinktomorrow\Chief\Tests\Fakes\HasCollectionIdFakeModel;
use Thinktomorrow\Chief\Tests\TestCase;


class CollectionItemsTest extends TestCase
{
    /** @test */
    function it_can_create_a_collection_from_hasCollectionId_entries()
    {
        $first = new HasCollectionIdFake(1, 'first', 'group');
        $second = new HasCollectionIdFake(2, 'second', 'group');

        $collectionItems = CollectionItems::fromCollection(collect([
            $first, $second
        ]));

        $this->assertInstanceOf(CollectionItems::class, $collectionItems);
        $this->assertInstanceOf(Collection::class, $collectionItems);
        $this->assertEquals($first->getCollectionId(), $collectionItems[0]->getCollectionId());
        $this->assertEquals($second->getCollectionId(), $collectionItems[1]->getCollectionId());
    }

    /** @test */
    function it_can_provide_collection_details()
    {
        $first = new HasCollectionIdFake(1, 'first', 'group');
        $second = new HasCollectionIdFake(2, 'second', 'group');

        $collectionItems = CollectionItems::fromCollection(collect([
            $first, $second
        ]));

        foreach($collectionItems->details() as $k => $detail){
            $this->assertEquals($collectionItems[$k]->getCollectionId(), $detail['id']);
            $this->assertEquals($collectionItems[$k]->getCollectionLabel(), $detail['label']);
            $this->assertEquals($collectionItems[$k]->getCollectionGroup(), $detail['group']);
        }
    }

    /** @test */
    function it_can_create_collection_id_from_string()
    {
        $this->setUpDatabase();
        HasCollectionIdFakeModel::migrateUp();

        $this->app['config']->set('thinktomorrow.chief.collections.pages', [
            'has_collection_fakes' => HasCollectionIdFakeModel::class,
        ]);

        $hasCollection = HasCollectionIdFakeModel::create(['label' => 'new label']);

        $this->assertInstanceOf(CollectionId::class, $hasCollection->getCollectionId());

        $id = CollectionId::fromString(HasCollectionIdFakeModel::class.'@'.$hasCollection->id);
        $this->assertTrue($id->equals($hasCollection->getCollectionId()));
    }

    /** @test */
    function it_can_instantiate_multiple_collection_ids()
    {
        $this->setUpDatabase();
        HasCollectionIdFakeModel::migrateUp();

        $first = HasCollectionIdFakeModel::create();
        $second = HasCollectionIdFakeModel::create();

        $instances = CollectionItems::inflate(collect([
            $first->getCollectionId()->get(),
            $second->getCollectionId()->get()
        ]));

        $this->assertCount(2, $instances);

        foreach($instances as $instance){
            $this->assertInstanceOf(Model::class, $instance);
        }
    }
}
