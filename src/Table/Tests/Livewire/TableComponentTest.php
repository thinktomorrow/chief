<?php

namespace Thinktomorrow\Chief\Table\Tests\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Filters\SelectFilter;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilteredTreeBreadcrumbTableFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ScopedTableStateFixture;
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

    public function test_it_shows_select_filter_label_for_scalar_default_value(): void
    {
        $table = Table::make()
            ->setTableReference(new Table\References\TableReference('xxx', 'table'))
            ->query(fn () => TreeModelFixture::query())
            ->filters([
                SelectFilter::make('period')
                    ->options(['current' => 'Huidige'])
                    ->value('current'),
            ])
            ->columns([
                ColumnText::make('id'),
                ColumnText::make('title'),
            ]);

        $component = Livewire::test(TableComponent::class, ['table' => $table]);

        $this->assertSame('Huidige', $component->instance()->getActiveFilterValue('period'));
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

    public function test_it_keeps_filter_state_per_scoped_filter_value(): void
    {
        $table = ScopedTableStateFixture::scopedFilters();

        $component = Livewire::test(TableComponent::class, ['table' => $table])
            ->set('filters.title', 'child1 title')
            ->set('filters.period', 'archived');

        $this->assertArrayNotHasKey('title', $component->instance()->filters);

        $component
            ->set('filters.title', 'child2 title')
            ->set('filters.period', 'current')
            ->assertSet('filters.title', 'child1 title')
            ->set('filters.period', 'archived')
            ->assertSet('filters.title', 'child2 title');

        $this->assertStringNotContainsString('scoped_state', json_encode(session()->all()));
    }

    public function test_reset_filters_forgets_the_previous_scoped_filter_session_key(): void
    {
        $table = ScopedTableStateFixture::scopedFilters();

        Livewire::test(TableComponent::class, ['table' => $table])
            ->set('filters.period', 'archived')
            ->set('filters.title', 'child2 title')
            ->call('resetFilters')
            ->assertSet('filters.period', 'archived')
            ->assertSet('filters.title', null)
            ->set('filters.period', 'current')
            ->set('filters.title', 'child1 title')
            ->set('filters.period', 'archived')
            ->assertSet('filters.title', null)
            ->set('filters.period', 'current')
            ->assertSet('filters.title', 'child1 title');
    }

    public function test_scoped_filter_can_limit_the_keys_it_scopes(): void
    {
        $table = ScopedTableStateFixture::limitedScopedFilters();

        Livewire::test(TableComponent::class, ['table' => $table])
            ->set('filters.title', 'child1 title')
            ->set('filters.status', 'open')
            ->set('filters.period', 'archived')
            ->assertSet('filters.status', 'open')
            ->assertSet('filters.title', null);
    }

    public function test_it_keeps_sorter_state_per_scoped_filter_value(): void
    {
        $table = ScopedTableStateFixture::scopedSorters();

        $component = Livewire::test(TableComponent::class, ['table' => $table])
            ->set('sorters.title_desc', 'desc')
            ->set('filters.period', 'archived');

        $this->assertArrayNotHasKey('title_desc', $component->instance()->sorters);

        $component
            ->set('sorters.title_desc', 'asc')
            ->set('filters.period', 'current')
            ->assertSet('sorters.title_desc', 'desc')
            ->set('filters.period', 'archived')
            ->assertSet('sorters.title_desc', 'asc');
    }

    public function test_select_filter_options_receive_active_table_filters(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => ScopedTableStateFixture::optionsFromActiveFilters()])
            ->set('filters.period', 'archived');

        $titleFilter = collect($component->instance()->getFilters())
            ->first(fn ($filter) => $filter->getKey() === 'title');

        $this->assertSame([
            ['value' => 'archived title', 'label' => 'Archived title'],
        ], $titleFilter->getOptions());
    }
}
