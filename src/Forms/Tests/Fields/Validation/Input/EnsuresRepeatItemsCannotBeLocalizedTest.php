<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\Input;

use Thinktomorrow\Chief\Forms\Exceptions\RepeatItemsCannotBeLocalized;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class EnsuresRepeatItemsCannotBeLocalizedTest extends FormsTestCase
{
    public function test_field_has_default_no_validation()
    {
        $this->expectException(RepeatItemsCannotBeLocalized::class);

        Repeat::make('xxx')->items([
            Text::make('title')->locales(),
            Text::make('description'),
        ]);
    }

    public function test_nested_field_has_default_no_validation()
    {
        $this->expectException(RepeatItemsCannotBeLocalized::class);

        Repeat::make('xxx')->items([
            Grid::make()->items([
                Text::make('title')->locales(),
            ]),
        ]);
    }
}
