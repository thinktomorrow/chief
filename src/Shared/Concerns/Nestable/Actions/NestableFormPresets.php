<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Layouts\Form;

class NestableFormPresets
{
    public static function parentSelect($model): iterable
    {
        yield Form::make('nestable_parent_form')
            ->title('Bovenliggend item')
            ->position('aside')
            ->items([
                MultiSelect::make('parent_id')
                    ->description('Onder welk item hoort deze thuis?')
                    ->options(fn () => app(SelectOptions::class)->getParentOptions($model)),
            ]);
    }
}
