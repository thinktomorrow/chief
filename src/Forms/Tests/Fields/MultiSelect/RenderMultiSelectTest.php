<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\MultiSelect;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderMultiSelectTest extends ChiefTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_render_the_field_view()
    {
        $component = MultiSelect::make('xxx');
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    /** @test */
    public function it_can_render_the_localized_field_view()
    {
        $component = MultiSelect::make('xxx')
            ->setLocalizedFormKeyTemplate(':name.:locale')
            ->locales(['nl', 'en']);
        $this->assertStringContainsString('name="xxx[nl]', $component->toHtml());
        $this->assertStringContainsString('name="xxx[en]', $component->toHtml());
    }

    /** @test */
    public function it_can_render_field_in_a_window()
    {
        $component = MultiSelect::make('xxx')->editInSidebar()
            ->options(['foobar'])
            ->value('foobar');

        $this->assertStringContainsString('foobar', $component->toHtml());
        $this->assertStringNotContainsString('name="xxx', $component->toHtml());
    }

    /** @test */
    public function it_can_render_the_multiple_field_view()
    {
        $component = MultiSelect::make('xxx')->multiple();
        $this->assertStringContainsString('multiple', $component->toHtml());
    }

    public function test_it_can_render_pairs()
    {
        $options = [
            ['value' => 'one', 'label' => 'een'],
            ['value' => 'two', 'label' => 'twee'],
            ['value' => 'three', 'label' => 'drie'],
            ['value' => 'four', 'label' => 'vier'],
        ];

        $component = MultiSelect::make('xxx')->options($options);
        $this->assertStringContainsString('<option value="one">een</option>', $component->toHtml());
    }
}
