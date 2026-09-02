<?php

namespace Thinktomorrow\Chief\Table\Tests\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Sorters\TreeSort;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Table\References\TableReference;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeResourceFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;
use Thinktomorrow\Chief\Table\UI\Livewire\DataTable;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        TreeModelFixture::migrateUp();

        TreeModelFixture::insert(array_map(
            fn (int $index): array => ['title' => sprintf('row-%03d', $index)],
            range(1, 65),
        ));
    }

    public function test_it_configures_pagination_and_items_per_page_selection(): void
    {
        $defaultTable = Table::make();

        $this->assertSame(20, $defaultTable->getPaginatePerPage());
        $this->assertSame([20, 50, 100, 200], $defaultTable->getItemsPerPageSelection());

        $table = Table::make()->paginate(25, [100, 50]);

        $this->assertSame(25, $table->getPaginatePerPage());
        $this->assertSame([25, 50, 100], $table->getItemsPerPageSelection());
        $this->assertTrue($table->isItemsPerPageSelectionAllowed());

        $table->itemsPerPageSelection([50, 20, 50]);

        $this->assertSame([20, 25, 50], $table->getItemsPerPageSelection());
        $this->assertFalse($table->disallowItemsPerPageSelection()->isItemsPerPageSelectionAllowed());
    }

    public function test_it_rejects_invalid_items_per_page_options(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Table::make()->itemsPerPageSelection([20, 0]);
    }

    public function test_it_changes_the_query_page_size_and_resets_the_page(): void
    {
        $component = Livewire::test(DataTable::class, ['table' => PaginationTableFixture::queryTable()])
            ->call('gotoPage', 2, 'pagequerytable')
            ->set('selectedItemsPerPage', 50)
            ->assertSet('selectedItemsPerPage', 50)
            ->assertSet('paginators.pagequerytable', 1);

        $this->assertSame(50, $component->instance()->resultPageCount);
        $this->assertSame([
            'itemsPerPage' => 50,
            'page' => 1,
        ], session($this->paginationSessionKey('queryTable')));
    }

    public function test_it_restores_items_per_page_and_page_from_the_session(): void
    {
        session()->put($this->paginationSessionKey('queryTable'), [
            'itemsPerPage' => 50,
            'page' => 2,
        ]);

        $component = Livewire::test(DataTable::class, ['table' => PaginationTableFixture::queryTable()])
            ->assertSet('selectedItemsPerPage', 50)
            ->assertSet('paginators.pagequerytable', 2);

        $this->assertSame(15, $component->instance()->resultPageCount);
    }

    public function test_an_explicit_url_page_takes_precedence_over_the_session(): void
    {
        session()->put($this->paginationSessionKey('queryTable'), [
            'itemsPerPage' => 20,
            'page' => 3,
        ]);

        $component = Livewire::withQueryParams(['pagequerytable' => 2])
            ->test(DataTable::class, ['table' => PaginationTableFixture::queryTable()])
            ->assertSet('selectedItemsPerPage', 20)
            ->assertSet('paginators.pagequerytable', 2);

        $this->assertSame(20, $component->instance()->resultPageCount);
        $this->assertSame(2, session($this->paginationSessionKey('queryTable'))['page']);
    }

    public function test_it_ignores_an_invalid_items_per_page_value(): void
    {
        session()->put($this->paginationSessionKey('queryTable'), [
            'itemsPerPage' => 999,
            'page' => 1,
        ]);

        Livewire::test(DataTable::class, ['table' => PaginationTableFixture::queryTable()])
            ->assertSet('selectedItemsPerPage', 20)
            ->set('selectedItemsPerPage', 999)
            ->assertSet('selectedItemsPerPage', 20);

        $this->assertSame(20, session($this->paginationSessionKey('queryTable'))['itemsPerPage']);
    }

    public function test_it_can_hide_the_items_per_page_selection(): void
    {
        session()->put($this->paginationSessionKey('fixedTable'), [
            'itemsPerPage' => 100,
            'page' => 1,
        ]);

        $component = Livewire::test(DataTable::class, ['table' => PaginationTableFixture::fixedTable()])
            ->assertSet('selectedItemsPerPage', 30)
            ->assertDontSee('Aantal resultaten per pagina');

        $this->assertSame(30, $component->instance()->resultPageCount);
    }

    public function test_it_paginates_collection_results(): void
    {
        $component = Livewire::test(DataTable::class, ['table' => PaginationTableFixture::collectionTable()])
            ->set('selectedItemsPerPage', 50);

        $this->assertSame(50, $component->instance()->resultPageCount);
    }

    public function test_it_paginates_tree_results_with_the_selected_page_size(): void
    {
        $component = Livewire::test(DataTable::class, ['table' => PaginationTableFixture::treeTable()])
            ->set('selectedItemsPerPage', 50);

        $this->assertSame(50, $component->instance()->resultPageCount);
    }

    public function test_it_renders_the_items_per_page_selection(): void
    {
        Livewire::test(DataTable::class, ['table' => PaginationTableFixture::queryTable()])
            ->assertSee('Aantal resultaten per pagina')
            ->assertSeeHtml('wire:model.live.change.number="selectedItemsPerPage"');
    }

    private function paginationSessionKey(string $tableMethod): string
    {
        return 'table.pagination.'.PaginationTableFixture::class.'::'.$tableMethod.'?params=';
    }
}

final class PaginationTableFixture
{
    public static function queryTable(): Table
    {
        return self::makeTable('queryTable')
            ->query(fn () => TreeModelFixture::query()->orderBy('id'));
    }

    public static function fixedTable(): Table
    {
        return self::makeTable('fixedTable')
            ->query(fn () => TreeModelFixture::query()->orderBy('id'))
            ->paginate(30)
            ->disallowItemsPerPageSelection();
    }

    public static function collectionTable(): Table
    {
        return self::makeTable('collectionTable')
            ->rows(fn () => TreeModelFixture::query()->orderBy('id')->get());
    }

    public static function treeTable(): Table
    {
        return self::makeTable('treeTable')
            ->query(fn () => TreeModelFixture::query())
            ->returnResultsAsTree()
            ->setTreeResource(new TreeResourceFixture)
            ->sorters([TreeSort::default()]);
    }

    private static function makeTable(string $method): Table
    {
        return Table::make()
            ->setTableReference(new TableReference(self::class, $method))
            ->columns([
                ColumnText::make('title'),
            ]);
    }
}
