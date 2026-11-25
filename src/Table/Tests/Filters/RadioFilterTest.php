<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Filters;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Filters\RadioFilter;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterTableFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class RadioFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FilterModelFixture::migrateUp();

        FilterModelFixture::create(['title' => 'news title', 'category' => 'news']);
        FilterModelFixture::create(['title' => 'newsletter title', 'category' => 'newsletter']);
        FilterModelFixture::create(['title' => 'blog title', 'category' => 'blog']);
    }

    public function test_it_renders_its_options_in_the_table_header(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::radio()])
            ->assertSuccessful()
            ->assertSee('Categorie')
            ->assertSee('Nieuwsbrief')
            ->assertSeeHtml('type="radio"')
            ->assertSee('Pas filter toe')
            ->assertSee('Wis selectie')
            ->assertDontSee('Annuleer');
    }

    public function test_it_filters_on_the_exact_value(): void
    {
        Livewire::test(TableComponent::class, ['table' => FilterTableFixture::radio()])
            ->set('filters.category', 'news')
            ->assertSee('news title')
            ->assertDontSee('newsletter title')
            ->assertDontSee('blog title');
    }

    public function test_it_shows_the_label_of_the_active_value(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::radio()])
            ->set('filters.category', 'newsletter');

        $this->assertSame('Nieuwsbrief', $component->instance()->getActiveFilterValue('category'));
    }

    public function test_it_can_clear_its_selection(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::radio()])
            ->set('filters.category', 'news')
            ->assertSee('Wis selectie')
            ->set('filters.category', null);

        $this->assertArrayNotHasKey('category', $component->get('filters'));
        $component->assertSee('blog title');
    }

    public function test_it_is_hidden_without_options(): void
    {
        $this->assertFalse(RadioFilter::make('category')->options([])->shouldInitiallyRender());
    }
}
