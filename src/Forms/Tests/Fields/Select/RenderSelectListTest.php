<?php

namespace Fields\Select;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Forms\Fields\SelectList;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderSelectListTest extends ChiefTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_render_the_field_view()
    {
        $component = SelectList::make('xxx');
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_the_localized_field_view()
    {
        $component = SelectList::make('xxx')
            ->setLocalizedFormKeyTemplate(':name.:locale')
            ->locales(['nl', 'en']);
        $this->assertStringContainsString('name="xxx[nl]', $component->toHtml());
        $this->assertStringContainsString('name="xxx[en]', $component->toHtml());
    }

    public function test_it_can_render_field_window()
    {
        $component = SelectList::make('xxx')->editInSidebar()
            ->options(['foobar'])
            ->value('foobar');

        $this->assertStringContainsString('foobar', $component->toHtml());
        $this->assertStringNotContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_pairs()
    {
        $component = SelectList::make('xxx')->options([
            ['value' => 'one', 'label' => 'een'],
            ['value' => 'two', 'label' => 'twee'],
            ['value' => 'three', 'label' => 'drie'],
            ['value' => 'four', 'label' => 'vier'],
        ]);
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_pairs_window()
    {
        $component = SelectList::make('xxx')->editInSidebar()
            ->options([
                ['value' => 'one', 'label' => 'een'],
                ['value' => 'two', 'label' => 'twee'],
                ['value' => 'three', 'label' => 'drie'],
                ['value' => 'four', 'label' => 'vier'],
            ])
            ->value('two');

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringNotContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_closure_as_option()
    {
        $component = SelectList::make('xxx')->editInSidebar()
            ->options(function(){
                return [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                    ['value' => 'three', 'label' => 'drie'],
                    ['value' => 'four', 'label' => 'vier'],
                ];
            })->value('two');

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringNotContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_grouped_options_field()
    {
        $component = SelectList::make('xxx')
            ->options([
                ['label' => 'first group', 'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ]],
                ['label' => 'second  group', 'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                ]],
            ])
            ->value('twee');

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_grouped_options_window()
    {
        $component = SelectList::make('xxx')->editInSidebar()
            ->options([
                ['label' => 'first group', 'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ]],
                ['label' => 'second  group', 'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                ]],
            ])
            ->value('twee');

        $this->assertStringContainsString('twee', $component->toHtml());
    }

    public function test_it_can_render_the_multiple_select_field()
    {
        $component = SelectList::make('xxx')->multiple();
        $this->assertStringContainsString('multiple', $component->toHtml());
    }

    public function test_it_can_render_multiple_select_window()
    {
        $component = SelectList::make('xxx')->editInSidebar()
            ->multiple()
            ->options([
                ['value' => 'one', 'label' => 'een'],
                ['value' => 'two', 'label' => 'twee'],
                ['value' => 'three', 'label' => 'drie'],
            ])
            ->value(['two', 'three']);

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringContainsString('drie', $component->toHtml());
    }

    public function test_it_can_render_multiple_grouped_options_field()
    {
        $component = SelectList::make('xxx')
            ->multiple()
            ->options([
                ['label' => 'first group', 'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ]],
                ['label' => 'second  group', 'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                ]],
            ])
            ->value(['two', 'three']);

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringContainsString('drie', $component->toHtml());
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_multiple_grouped_options_window()
    {
        $component = SelectList::make('xxx')->editInSidebar()
            ->multiple()
            ->options([
                ['label' => 'first group', 'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ]],
                ['label' => 'second  group', 'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                ]],
            ])
            ->value(['two', 'three']);

        $this->assertStringContainsString('twee', $component->toHtml());
        $this->assertStringContainsString('drie', $component->toHtml());
    }
}
