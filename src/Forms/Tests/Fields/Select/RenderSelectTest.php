<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Select;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Forms\Fields\Select;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderSelectTest extends ChiefTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_render_the_field_view()
    {
        $component = Select::make('xxx');
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_the_localized_field_view()
    {
        $component = Select::make('xxx')
            ->setFieldNameTemplate(':name.:locale')
            ->locales(['nl', 'en']);
        $this->assertStringContainsString('name="xxx[nl]', $component->toHtml());
        $this->assertStringContainsString('name="xxx[en]', $component->toHtml());
    }

    public function test_it_can_render_field_window()
    {
        $component = Select::make('xxx')
            ->options(['foobar'])
            ->value('foobar');

        $this->assertStringContainsString('foobar', $component->renderPreview()->render());
        $this->assertStringNotContainsString('name="xxx', $component->renderPreview()->render());
    }

    public function test_it_can_render_pairs()
    {
        $component = Select::make('xxx')->options([
            ['value' => 'one', 'label' => 'een'],
            ['value' => 'two', 'label' => 'twee'],
            ['value' => 'three', 'label' => 'drie'],
            ['value' => 'four', 'label' => 'vier'],
        ]);
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_pairs_window()
    {
        $component = Select::make('xxx')
            ->options([
                ['value' => 'one', 'label' => 'een'],
                ['value' => 'two', 'label' => 'twee'],
                ['value' => 'three', 'label' => 'drie'],
                ['value' => 'four', 'label' => 'vier'],
            ])
            ->value('two');

        $this->assertStringContainsString('twee', $component->renderPreview()->render());
        $this->assertStringNotContainsString('name="xxx', $component->renderPreview()->render());
    }

    public function test_it_can_render_grouped_options_field()
    {
        $component = Select::make('xxx')
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
        $component = Select::make('xxx')
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
        $component = Select::make('xxx')->multiple();
        $this->assertStringContainsString('multiple', $component->toHtml());
    }

    public function test_it_can_render_multiple_select_window()
    {
        $component = Select::make('xxx')
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
        $component = Select::make('xxx')
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
        $component = Select::make('xxx')
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
