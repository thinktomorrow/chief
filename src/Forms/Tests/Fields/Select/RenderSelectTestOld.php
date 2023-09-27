<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Select;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Forms\Fields\Select;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderSelectTestOld extends ChiefTestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_render_the_field_view()
    {
        $component = Select::make('xxx');
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    /** @test */
    public function it_can_render_the_localized_field_view()
    {
        $component = Select::make('xxx')
            ->setLocalizedFormKeyTemplate(':name.:locale')
            ->locales(['nl', 'en']);
        $this->assertStringContainsString('name="xxx[nl]', $component->toHtml());
        $this->assertStringContainsString('name="xxx[en]', $component->toHtml());
    }

    /** @test */
    public function it_can_render_field_in_a_window()
    {
        $component = Select::make('xxx')->editInSidebar()
            ->options(['foobar'])
            ->value('foobar');

        $this->assertStringContainsString('foobar', $component->toHtml());
        $this->assertStringNotContainsString('name="xxx', $component->toHtml());
    }

    /** @test */
    public function it_can_render_multiple_options_in_a_window()
    {
        $component = Select::make('xxx')->editInSidebar()
            ->options([
                ['value' => 'one', 'label' => 'een'],
                ['value' => 'two', 'label' => 'twee'],
                ['value' => 'three', 'label' => 'drie'],
            ])
            ->value(['twee', 'drie']);

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringContainsString('drie', $component->toHtml());
    }

    /** @test */
    public function it_can_render_grouped_options_in_a_window()
    {
        $component = Select::make('xxx')->editInSidebar()
            ->options([
                ['label' => 'first group', 'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ]],
                ['label' => 'second  group', 'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                ]],
            ])
            ->value(['twee', 'drie']);

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringContainsString('drie', $component->toHtml());
    }

    /** @test */
    public function it_can_render_the_multiple_field_view()
    {
        $component = Select::make('xxx')->multiple();
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

        $component = Select::make('xxx')->options($options);
        $this->assertStringContainsString('<option value="one">een</option>', $component->toHtml());
    }
}
