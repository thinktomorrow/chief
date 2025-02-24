<?php

namespace Thinktomorrow\Chief\Table\Tests\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeResourceFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class TableComponentTest extends TestCase
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

        $this->root = TreeModelFixture::create(['parent_id' => null, 'title' => 'root title']);
        $this->child1 = TreeModelFixture::create(['parent_id' => $this->root->id, 'title' => 'child1 title']);
        $this->child2 = TreeModelFixture::create(['parent_id' => $this->root->id, 'title' => 'child2 title']);
        $this->grandchild = TreeModelFixture::create(['parent_id' => $this->child1->id, 'title' => 'grandchild title']);
    }

    public function test_it_can_create_component()
    {
        $table = Table::make()
            ->setTableReference(new Table\References\TableReference('xxx', 'table'))
            ->query(fn () => TreeModelFixture::query())
            ->columns([
                ColumnText::make('id'),
                ColumnText::make('title'),
            ]);

        $component = Livewire::test(TableComponent::class, ['table' => $table]);

        $component->assertSuccessful();
        $component->assertSee('root title');
        $component->assertSee('child1 title');
        $component->assertSee('child2 title');
        $component->assertSee('grandchild title');
    }
}
