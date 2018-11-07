<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common\Morphables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Concerns\Morphable\CollectionDetails;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Tests\TestCase;

class FlatReferenceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        MorphableContractFakeModel::migrateUp();
    }

    /** @test */
    public function it_can_create_a_collection_from_flat_references()
    {
        $this->markTestIncomplete();

        $first = new MorphableContractFake(1, 'first', 'group');
        $second = new MorphableContractFake(2, 'second', 'group');

        $flatReferences = FlatReferenceCollection::make([$first, $second])->toFlatReferences();

        $this->assertInstanceOf(Collection::class, $flatReferences);
        $this->assertEquals($first->flatReference()->get(), $flatReferences[0]);
        $this->assertEquals($second->flatReference()->get(), $flatReferences[1]);
    }

    /** @test */
    public function it_can_create_collection_id_from_string()
    {
        $acts_as_collection = MorphableContractFakeModel::create(['label' => 'new label']);

        $this->assertInstanceOf(FlatReference::class, $acts_as_collection->flatReference());

        $id = FlatReferenceFactory::fromString(MorphableContractFakeModel::class.'@'.$acts_as_collection->id);
        $this->assertTrue($id->equals($acts_as_collection->flatReference()));
    }

    /** @test */
    public function it_can_create_instance_from_collection_id()
    {
        $first = MorphableContractFakeModel::create();
        $instance = $first->flatReference()->instance();

        $this->assertInstanceOf(MorphableContractFakeModel::class, $instance);
        $this->assertEquals($first->id, $instance->id);
    }

    /** @test */
    public function it_can_instantiate_multiple_collection_ids()
    {
        $first = MorphableContractFakeModel::create();
        $second = MorphableContractFakeModel::create();

        $instances = FlatReferenceCollection::fromFlatReferences(collect([
            $first->flatReference()->get(),
            $second->flatReference()->get()
        ]));

        $this->assertCount(2, $instances);

        foreach ($instances as $instance) {
            $this->assertInstanceOf(Model::class, $instance);
        }
    }
}
