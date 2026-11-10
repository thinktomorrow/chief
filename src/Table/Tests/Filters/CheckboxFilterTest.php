<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Filters;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Filters\CheckboxFilter;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterTableFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class CheckboxFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FilterModelFixture::migrateUp();

        FilterModelFixture::create(['title' => 'news title', 'category' => 'news']);
        FilterModelFixture::create(['title' => 'blog title', 'category' => 'blog']);
        FilterModelFixture::create(['title' => 'event title', 'category' => 'event']);
    }

    public function test_it_is_multiple_by_default(): void
    {
        $this->assertTrue(CheckboxFilter::make('category')->allowMultiple());
    }

    public function test_it_renders_its_options_in_the_table_header(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::checkbox()])
            ->assertSuccessful()
            ->assertSee('Categorie')
            ->assertSee('Nieuws')
            ->assertSee('Evenement')
            ->assertSee('Pas filter toe')
            ->assertSee('Wis selectie')
            ->assertDontSee('Annuleer');
    }

    public function test_it_filters_on_all_checked_values(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::checkbox()])
            ->set('filters.category', ['news', 'event'])
            ->assertSee('news title')
            ->assertSee('event title')
            ->assertDontSee('blog title');
    }

    public function test_it_shows_the_labels_of_the_active_values(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::checkbox()])
            ->set('filters.category', ['news', 'event']);

        $this->assertSame('Nieuws, Evenement', $component->instance()->getActiveFilterValue('category'));
    }

    public function test_unchecking_all_values_removes_the_filter(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::checkbox()])
            ->set('filters.category', ['news'])
            ->set('filters.category', []);

        $this->assertArrayNotHasKey('category', $component->get('filters'));
        $component->assertSee('blog title');
    }
}
