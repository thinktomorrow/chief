<?php

namespace Thinktomorrow\Chief\Table\Tests\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilteredTreeBreadcrumbTableFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeModelFixture;
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

    public function test_it_shows_filtered_tree_breadcrumbs_per_item(): void
    {
        $table = FilteredTreeBreadcrumbTableFixture::make();

        Livewire::test(TableComponent::class, ['table' => $table])
            ->set('sorters', ['title_asc' => 'title_asc'])
            ->set('filters.title', 'grandchild')
            ->assertSee('Structuur')
            ->assertSee('root title')
            ->assertSee('child1 title')
            ->assertSee('grandchild title');
    }

    public function test_it_allows_hiding_filtered_tree_breadcrumbs_via_column_selection(): void
    {
        $table = FilteredTreeBreadcrumbTableFixture::make();

        Livewire::test(TableComponent::class, ['table' => $table])
            ->set('sorters', ['title_asc' => 'title_asc'])
            ->set('columnSelection', ['title'])
            ->set('filters.title', 'grandchild')
            ->assertDontSee('child1 title')
            ->assertSee('grandchild title');
    }

    public function test_it_shows_tree_breadcrumbs_when_filtered_even_with_tree_sorting_active(): void
    {
        $table = FilteredTreeBreadcrumbTableFixture::make();

        $component = Livewire::test(TableComponent::class, ['table' => $table])
            ->set('sorters', ['tree-sorting' => 'tree-sorting'])
            ->set('filters.title', 'grandchild')
            ->assertSee('child1 title')
            ->assertSee('grandchild title');

        $this->assertTrue($component->instance()->shouldShowTreeBreadcrumbColumn());
    }

    public function test_it_does_not_show_tree_breadcrumbs_when_tree_results_are_rendered(): void
    {
        $table = FilteredTreeBreadcrumbTableFixture::make();

        $component = Livewire::test(TableComponent::class, ['table' => $table])
            ->set('sorters', ['tree-sorting' => 'tree-sorting']);

        $this->assertFalse($component->instance()->shouldShowTreeBreadcrumbColumn());
    }
}
