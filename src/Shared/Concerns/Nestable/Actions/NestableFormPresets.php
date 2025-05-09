<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Layouts\Form;

class NestableFormPresets
{
    public static function parentSelect($model): iterable
    {
        yield Form::make('nestable_parent_form')
            ->title('Paginastructuur')
            ->position('aside')
            ->displayAsTransparentForm()
            ->items([
                MultiSelect::make('parent_id')
                    ->label('Bovenliggend item')
                    ->description('Onder welk item hoort deze thuis?')
                    ->options(fn () => app(SelectOptions::class)->getParentOptions($model)),
            ]);
    }
}
