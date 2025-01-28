<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Select;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\InvalidOptionsForMultiSelect;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class OptionsTest extends ChiefTestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_non_assoc_options_use_value_as_label_and_option_value()
    {
        $component = MultiSelect::make('xxx')
            ->options(['foo', 'bar']);

        $this->assertEquals([
            ['value' => 'foo', 'label' => 'foo'],
            ['value' => 'bar', 'label' => 'bar'],
        ], $component->getOptions());
    }

    public function test_it_can_set_assoc_options()
    {
        $component = MultiSelect::make('xxx')->options([
            'one' => 'een',
            'two' => 'twee',
        ]);

        $this->assertEquals([
            ['value' => 'one', 'label' => 'een'],
            ['value' => 'two', 'label' => 'twee'],
        ], $component->getOptions());
    }

    public function test_it_can_set_options_as_pairs()
    {
        $options = [
            ['value' => 'one', 'label' => 'een'],
            ['value' => 'two', 'label' => 'twee'],
            ['value' => 'three', 'label' => 'drie'],
            ['value' => 'four', 'label' => 'vier'],
        ];

        $component = MultiSelect::make('xxx')->options($options);

        $this->assertEquals([
            ['value' => 'one', 'label' => 'een'],
            ['value' => 'two', 'label' => 'twee'],
            ['value' => 'three', 'label' => 'drie'],
            ['value' => 'four', 'label' => 'vier'],
        ], $component->getOptions());
    }

    public function test_it_can_set_grouped_options_as_pairs()
    {
        $options = [
            [
                'label' => 'Group one',
                'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ],
            ],
            [
                'label' => 'Group two',
                'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                    ['value' => 'four', 'label' => 'vier'],
                ],
            ],
        ];

        $component = MultiSelect::make('xxx')->options($options);

        $this->assertEquals([
            [
                'label' => 'Group one',
                'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ],
            ],
            [
                'label' => 'Group two',
                'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                    ['value' => 'four', 'label' => 'vier'],
                ],
            ],
        ], $component->getOptions());
    }

    public function test_it_can_set_grouped_options_for_multiselect_choices_library()
    {
        $options = [
            [
                'label' => 'Group one',
                'options' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ],
            ],
            [
                'label' => 'Group two',
                'options' => [
                    ['value' => 'three', 'label' => 'drie'],
                    ['value' => 'four', 'label' => 'vier'],
                ],
            ],
        ];

        $component = MultiSelect::make('xxx')->options($options);

        $this->assertEquals([
            [
                'label' => 'Group one',
                'choices' => [
                    ['value' => 'one', 'label' => 'een'],
                    ['value' => 'two', 'label' => 'twee'],
                ],
            ],
            [
                'label' => 'Group two',
                'choices' => [
                    ['value' => 'three', 'label' => 'drie'],
                    ['value' => 'four', 'label' => 'vier'],
                ],
            ],
        ], $component->getMultiSelectFieldOptions());
    }

    public function test_it_can_set_grouped_assoc_options()
    {
        $component = MultiSelect::make('xxx')->options([
            [
                'label' => 'Group one',
                'options' => [
                    'one' => 'een',
                ],
            ],
            [
                'label' => 'Group two',
                'options' => [
                    'two' => 'twee',
                ],
            ],

        ]);

        $this->assertEquals([
            [
                'label' => 'Group one',
                'options' => [
                    ['value' => 'one', 'label' => 'een'],
                ],
            ],
            [
                'label' => 'Group two',
                'options' => [
                    ['value' => 'two', 'label' => 'twee'],
                ],
            ],
        ], $component->getOptions());
    }

    public function test_it_halts_invalid_nested_options()
    {
        $this->expectException(InvalidOptionsForMultiSelect::class);
        $this->expectExceptionMessage('Invalid MultiSelect option passed: [one:een]');

        $component = MultiSelect::make('xxx')->options([
            ['one' => 'een'],
            ['two' => 'twee'],
        ]);

        $component->getOptions();
    }

    public function test_it_can_check_if_closure_options_are_grouped()
    {
        $component = MultiSelect::make('xxx')->options(fn () => [
            [
                'label' => 'Group one',
                'options' => [
                    'one' => 'een',
                ],
            ],
        ]);

        $this->assertTrue($component->hasOptionGroups());
    }

    public function test_it_can_assume_options_are_grouped_without_calling_closure()
    {
        $component = MultiSelect::make('xxx')->options(function () {
            throw new \Exception('Should not be called');
        })->grouped();

        $this->assertTrue($component->hasOptionGroups());
    }
}
