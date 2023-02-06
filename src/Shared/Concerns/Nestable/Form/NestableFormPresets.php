<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Form;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Form;

class NestableFormPresets
{
    public static function parentSelect($model): iterable
    {
        yield Form::make('nestable_parent_form')
            ->title('Bovenliggende pagina')
            ->position('aside')
            ->items([
                MultiSelect::make('parent_id')
                    ->description('Onder welke pagina hoort deze thuis.')
                    ->options(fn () => app(SelectOptions::class)->getParentOptions($model)),
            ]);
    }
}
