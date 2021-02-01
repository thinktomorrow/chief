<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferenceCollection;

class ModelReferenceTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_can_create_a_collection_from_model_references()
    {
        $first = new ArticlePage(['id' => 1, 'title' => 'first']);
        $second = new ArticlePage(['id' => 2, 'title' => 'second']);

        $modelReferences = ModelReferenceCollection::make([$first, $second])->toModelReferences();

        $this->assertInstanceOf(Collection::class, $modelReferences);
        $this->assertEquals($first->modelReference()->get(), $modelReferences[0]);
        $this->assertEquals($second->modelReference()->get(), $modelReferences[1]);
    }

    /** @test */
    public function it_can_create_collection_id_from_string()
    {
        $acts_as_collection = ArticlePage::create(['title' => 'new title']);

        $this->assertInstanceOf(ModelReference::class, $acts_as_collection->modelReference());

        $id = ModelReference::fromString(ArticlePage::class.'@'.$acts_as_collection->id);
        $this->assertTrue($id->equals($acts_as_collection->modelReference()));
    }

    /** @test */
    public function it_can_create_instance_from_collection_id()
    {
        $first = ArticlePage::create();
        $instance = $first->modelReference()->instance();

        $this->assertInstanceOf(ArticlePage::class, $instance);
        $this->assertEquals($first->id, $instance->id);
    }

    /** @test */
    public function it_can_instantiate_multiple_collection_ids()
    {
        $first = ArticlePage::create();
        $second = ArticlePage::create();

        $instances = ModelReferenceCollection::fromModelReferences(collect([
            $first->modelReference()->get(),
            $second->modelReference()->get()
        ]));

        $this->assertCount(2, $instances);

        foreach ($instances as $instance) {
            $this->assertInstanceOf(Model::class, $instance);
        }
    }
}
