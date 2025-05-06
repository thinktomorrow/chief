<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\UI\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\UI\Livewire\RepeatComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class RepeatComponentTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_add_a_section()
    {
        $repeat = Repeat::make('blocks')->components([
            Text::make('title'),
            Text::make('description'),
        ]);

        Livewire::test(RepeatComponent::class, [
            'field' => $repeat,
            'locale' => 'nl',
        ])
            ->set('form', [['title' => 'Foo', 'description' => 'Bar']])
            ->call('addSection')
            ->assertSet('form.1.title', null)
            ->assertSet('form.1.description', null);
    }

    public function test_it_can_remove_a_section()
    {
        $repeat = Repeat::make('blocks')->components([
            Text::make('title'),
        ]);

        Livewire::test(RepeatComponent::class, [
            'field' => $repeat,
        ])
            ->set('form', [['title' => 'First'], ['title' => 'Second']])
            ->call('removeSection', 0)
            ->assertSet('form.0.title', 'Second')
            ->assertCount('form', 1);
    }

    public function test_it_can_reorder_sections()
    {
        $repeat = Repeat::make('blocks')->components([
            Text::make('title'),
        ]);

        Livewire::test(RepeatComponent::class, [
            'field' => $repeat,
        ])
            ->set('form', [
                ['title' => 'A'],
                ['title' => 'B'],
                ['title' => 'C'],
            ])
            ->call('reorder', [2, 0, 1])
            ->assertSet('form.0.title', 'C')
            ->assertSet('form.1.title', 'A')
            ->assertSet('form.2.title', 'B');
    }

    public function test_it_can_set_form_data()
    {
        $repeat = Repeat::make('blocks')->components([
            Text::make('title'),
            Text::make('description'),
        ]);

        Livewire::test(RepeatComponent::class, [
            'field' => $repeat,
            'locale' => 'nl',
        ])
            ->set('form', [['title' => 'Foo', 'description' => 'Bar']])
            ->assertSet('form.0.title', 'Foo')
            ->assertSet('form.0.description', 'Bar');
    }
}
