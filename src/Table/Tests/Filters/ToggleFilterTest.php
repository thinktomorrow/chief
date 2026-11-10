<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Filters;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Filters\ToggleFilter;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterTableFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class ToggleFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FilterModelFixture::migrateUp();

        FilterModelFixture::create(['title' => 'featured title', 'is_featured' => true]);
        FilterModelFixture::create(['title' => 'regular title', 'is_featured' => false]);
    }

    public function test_it_renders_in_the_table_header(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::toggle()])
            ->assertSuccessful()
            ->assertSee('Enkel uitgelicht')
            ->assertSeeHtml('form-input-toggle');
    }

    public function test_it_falls_back_to_the_label(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::toggleWithoutOptionLabel()])
            ->assertSee('Uitgelicht');
    }

    public function test_it_only_applies_the_query_when_switched_on(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::toggle()])
            ->assertSee('featured title')
            ->assertSee('regular title')
            ->set('filters.is_featured', true)
            ->assertSee('featured title')
            ->assertDontSee('regular title')
            ->set('filters.is_featured', false)
            ->assertSee('regular title');
    }

    public function test_it_treats_url_values_as_booleans(): void
    {
        $filter = ToggleFilter::make('is_featured');

        $this->assertTrue($filter->isOn('true'));
        $this->assertTrue($filter->isOn('1'));
        $this->assertFalse($filter->isOn('false'));
        $this->assertFalse($filter->isOn(null));

        $query = FilterModelFixture::query();
        $filter->getQuery()($query, 'false');
        $this->assertCount(2, $query->get());

        $query = FilterModelFixture::query();
        $filter->getQuery()($query, 'true');
        $this->assertSame(['featured title'], $query->pluck('title')->all());
    }

    public function test_switching_off_removes_the_filter(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::toggle()])
            ->set('filters.is_featured', true)
            ->set('filters.is_featured', false);

        $this->assertArrayNotHasKey('is_featured', $component->get('filters'));
        $this->assertSame([], $component->instance()->getActiveFilters());
    }

    public function test_it_can_be_on_by_default(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::toggleOnByDefault()])
            ->assertSee('featured title')
            ->assertDontSee('regular title')
            ->set('filters.is_featured', false)
            ->assertSee('regular title');

        // Switching off a default-on toggle is kept so the default isn't restored
        $this->assertFalse($component->get('filters')['is_featured']);
        $this->assertSame([], $component->instance()->getActiveFilters());
    }

    public function test_url_values_are_cast_to_booleans_so_the_switch_reflects_the_filter_state(): void
    {
        $component = Livewire::withQueryParams(['filters' => ['is_featured' => 'false']])
            ->test(TableComponent::class, ['table' => FilterTableFixture::toggleOnByDefault()])
            ->assertSee('regular title');

        $this->assertFalse($component->get('filters')['is_featured']);

        $component = Livewire::withQueryParams(['filters' => ['is_featured' => 'true']])
            ->test(TableComponent::class, ['table' => FilterTableFixture::toggle()])
            ->assertDontSee('regular title');

        $this->assertTrue($component->get('filters')['is_featured']);
    }

    public function test_default_only_accepts_booleans(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ToggleFilter::make('is_featured')->default('on');
    }

    public function test_default_off_resets_a_default_on(): void
    {
        $filter = ToggleFilter::make('is_featured')->defaultOn()->defaultOff();

        $this->assertFalse($filter->hasValue());
        $this->assertFalse($filter->isOnByDefault());
    }

    public function test_it_renders_the_option_description_in_the_filters_drawer(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::toggle()])
            ->assertSee('Enkel uitgelicht')
            ->assertDontSee('Toont enkel de uitgelichte items');

        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::tertiaryToggle()])
            ->assertSee('Enkel uitgelicht')
            ->assertSee('Toont enkel de uitgelichte items');
    }
}
