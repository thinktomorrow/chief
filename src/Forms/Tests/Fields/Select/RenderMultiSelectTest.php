<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Select;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderMultiSelectTest extends ChiefTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_render_the_field_view()
    {
        $component = MultiSelect::make('xxx');
        $this->assertStringContainsString('wire:model="form.xxx"', $component->toHtml());
    }

    public function test_it_can_render_the_localized_field_view()
    {
        $component = MultiSelect::make('xxx')
            ->setFieldNameTemplate(':name.:locale')
            ->locales(['nl', 'en']);
        $this->assertStringContainsString('wire:model="form.xxx.nl"', $component->toHtml());
        $this->assertStringContainsString('wire:model="form.xxx.en"', $component->toHtml());
    }

    public function test_it_can_render_field_window()
    {
        $component = MultiSelect::make('xxx')
            ->options(['foobar'])
            ->value('foobar');

        $this->assertStringContainsString('foobar', $component->renderPreview()->render());
        $this->assertStringNotContainsString('wire:model="form.xxx"', $component->renderPreview()->render());
    }

    public function test_it_can_render_pairs()
    {
        $component = MultiSelect::make('xxx')->options([
            ['value' => 'one', 'label' => 'een'],
            ['value' => 'two', 'label' => 'twee'],
            ['value' => 'three', 'label' => 'drie'],
            ['value' => 'four', 'label' => 'vier'],
        ]);
        $this->assertStringContainsString('wire:model="form.xxx"', $component->toHtml());
    }

    public function test_it_can_render_pairs_window()
    {
        $component = MultiSelect::make('xxx')
            ->options([
                ['value' => 'one', 'label' => 'een'],
                ['value' => 'two', 'label' => 'twee'],
                ['value' => 'three', 'label' => 'drie'],
                ['value' => 'four', 'label' => 'vier'],
            ])
            ->value('two');

        $this->assertStringContainsString('twee', $component->renderPreview()->render());
        $this->assertStringNotContainsString('wire:model="form.xxx"', $component->renderPreview()->render());
    }

    public function test_it_can_render_grouped_options_field()
    {
        $component = MultiSelect::make('xxx')
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
        $this->assertStringContainsString('wire:model="form.xxx"', $component->toHtml());
    }

    public function test_it_can_render_grouped_options_window()
    {
        $component = MultiSelect::make('xxx')
            ->options([
                ['label' => 'first group', 'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ]],
                ['label' => 'second  group', 'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                ]],
            ])
            ->value('two');

        $this->assertStringContainsString('twee', $component->renderPreview()->render());
    }

    public function test_it_can_render_the_multiple_select_field()
    {
        $component = MultiSelect::make('xxx')->multiple();
        $this->assertStringContainsString('multiple', $component->toHtml());
    }

    public function test_it_can_render_multiple_select_window()
    {
        $component = MultiSelect::make('xxx')
            ->multiple()
            ->options([
                ['value' => 'one', 'label' => 'een'],
                ['value' => 'two', 'label' => 'twee'],
                ['value' => 'three', 'label' => 'drie'],
            ])
            ->value(['two', 'three']);

        $this->assertStringContainsString('twee', $component->renderPreview()->render());
        $this->assertStringContainsString('drie', $component->renderPreview()->render());
    }

    public function test_it_can_render_multiple_grouped_options_field()
    {
        $component = MultiSelect::make('xxx')
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
        $this->assertStringContainsString('wire:model="form.xxx"', $component->toHtml());
    }

    public function test_it_can_render_multiple_grouped_options_window()
    {
        $component = MultiSelect::make('xxx')
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

        $this->assertStringContainsString('twee', $component->renderPreview()->render());
        $this->assertStringContainsString('drie', $component->renderPreview()->render());
    }
}
