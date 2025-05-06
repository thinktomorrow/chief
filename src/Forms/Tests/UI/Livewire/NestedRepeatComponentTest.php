<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\UI\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\UI\Livewire\RepeatComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class NestedRepeatComponentTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_handles_nested_components()
    {
        $repeat = Repeat::make('blocks')->components([
            Repeat::make('content')->components([
                Text::make('title'),
                Text::make('subtitle'),
            ]),
        ]);

        Livewire::test(RepeatComponent::class, [
            'field' => $repeat,
        ])
            ->set('form', [
                ['content' => ['title' => 'Foo', 'subtitle' => 'Bar']],
            ])
            ->call('addSection')
            ->assertSet('form.1.content.title', null)
            ->assertSet('form.1.content.subtitle', null);
    }

    public function test_it_can_populate_nested_components(): void
    {
        $repeat = Repeat::make('blocks')->components([
            Repeat::make('content')->components([
                Text::make('title'),
                Text::make('subtitle'),
            ]),
        ]);

        Livewire::test(RepeatComponent::class, [
            'field' => $repeat,
        ])
            ->set('form', [
                ['content' => ['title' => 'Foo', 'subtitle' => 'Bar']],
            ])
            ->assertSet('form.0.content.title', 'Foo')
            ->assertSet('form.0.content.subtitle', 'Bar');

    }
}
