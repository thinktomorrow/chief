<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Filters;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\FilterTableFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class FilterPlacementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FilterModelFixture::migrateUp();
    }

    public function test_filters_render_in_the_actions_bar(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::primaryFilters()])
            ->assertSuccessful()
            ->assertSee('Enkel uitgelicht')
            ->assertSee('Categorie')
            ->assertSee('Nieuws')
            ->assertSee('Status')
            ->assertSeeHtml('type="radio"');

        $this->assertSame(
            ['is_featured', 'category', 'state'],
            collect($component->instance()->getPrimaryFilters())->map->getKey()->values()->all()
        );
    }

    public function test_option_filters_render_in_the_filters_drawer(): void
    {
        $component = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::tertiaryOptionFilters()])
            ->assertSuccessful()
            ->assertSee('Meer filters')
            ->assertSee('Categorie')
            ->assertSee('Blog')
            ->assertSee('Status')
            ->assertSee('Offline');

        $this->assertSame(
            ['category', 'state'],
            collect($component->instance()->getTertiaryFilters())->map->getKey()->values()->all()
        );
    }

    public function test_a_toggle_in_the_drawer_does_not_repeat_its_label(): void
    {
        $html = Livewire::test(TableComponent::class, ['table' => FilterTableFixture::tertiaryToggleWithoutOptionLabel()])
            ->html();

        $this->assertSame(1, substr_count($html, 'Uitgelicht'));
    }
}
