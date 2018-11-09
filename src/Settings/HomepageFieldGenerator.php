<?php

namespace Thinktomorrow\Chief\Settings;

use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\SelectField;
use Thinktomorrow\Chief\Pages\Single;

class HomepageFieldGenerator
{
    public static function generate(): Field
    {
        $singles = Single::all();
        $singles = $singles->map(function ($single) {

            // Select label (from translatable title field)
            $single->label = $single->title;

            return $single;
        })->pluck('label', 'id')->toArray();

        return SelectField::make('homepage')
            ->label('Homepagina')
            ->description('Bepaal de homepagina van de site')
            ->options($singles)
            ->selected(chiefSetting('homepage'));
    }
}
