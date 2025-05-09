<?php

namespace Thinktomorrow\Chief\Table\Tests\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Table\Livewire\TreeModels;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeResourceFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class TreeModelsTest extends TestCase
{
    use RefreshDatabase;

    private $root;

    private $child1;

    private $child2;

    private $grandchild;

    protected function setUp(): void
    {
        parent::setUp();

        TreeModelFixture::migrateUp();
        $this->resource = new TreeResourceFixture;

        $this->root = TreeModelFixture::create(['parent_id' => null]);
        $this->child1 = TreeModelFixture::create(['parent_id' => $this->root->id]);
        $this->child2 = TreeModelFixture::create(['parent_id' => $this->root->id]);
        $this->grandchild = TreeModelFixture::create(['parent_id' => $this->child1->id]);
    }

    public function test_it_can_get_tree_model_ids()
    {
        $this->assertEquals([
            (object) ['id' => $this->root->id, 'parent_id' => null],
            (object) ['id' => $this->child1->id, 'parent_id' => $this->root->id],
            (object) ['id' => $this->child2->id, 'parent_id' => $this->root->id],
            (object) ['id' => $this->grandchild->id, 'parent_id' => $this->child1->id],
        ], $this->resource->getTreeModelIds());
    }

    public function test_it_can_get_tree_models()
    {
        $treeModels = $this->resource->getTreeModels();

        $this->assertCount(4, $treeModels);

        $this->assertEquals($this->root->id, $treeModels[0]->id);
        $this->assertEquals($this->child1->id, $treeModels[1]->id);
        $this->assertEquals($this->grandchild->id, $treeModels[2]->id);
        $this->assertEquals($this->child2->id, $treeModels[3]->id);
    }

    public function test_it_can_compose_tree_models_with_limit(): void
    {
        $ids = [$this->child1->id, $this->child2->id, $this->grandchild->id];
        $offset = 0;
        $limit = 2;

        $treeModels = app(TreeModels::class)->compose($this->resource, $ids, $offset, $limit);

        $this->assertCount(2, $treeModels[1]);
        $this->assertEquals(1, $treeModels[1][0]->indent);
        $this->assertEquals(2, $treeModels[1][1]->indent);
    }

    public function test_it_can_compose_tree_models_filtered_by_ids(): void
    {
        $ids = [$this->child1->id, $this->child2->id, $this->grandchild->id];

        $treeModels = app(TreeModels::class)->compose($this->resource, $ids, 0, 10);

        $this->assertCount(3, $treeModels[1]);
        $this->assertEquals(1, $treeModels[1][0]->indent);
        $this->assertEquals(2, $treeModels[1][1]->indent);
        $this->assertEquals(1, $treeModels[1][2]->indent);
    }

    public function test_it_returns_empty_arrays_when_no_ids_are_provided(): void
    {
        $treeModels = app(TreeModels::class)->compose($this->resource, [], 0, 10);

        $this->assertCount(0, $treeModels[0]);
        $this->assertCount(0, $treeModels[1]);
    }
}
