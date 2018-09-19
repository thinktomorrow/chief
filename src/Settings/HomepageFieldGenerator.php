<?php

namespace Thinktomorrow\Chief\Settings;

use Thinktomorrow\Chief\Common\TranslatableFields\Field;

class HomepageFieldGenerator
{
    public static function generate(): Field
    {
        $singles = \Thinktomorrow\Chief\Pages\Single::all();
        $singles = $singles->map(function ($single) {

            // Select label (from translatable title field)
            $single->label = $single->title;

            return $single;
        })->pluck('label', 'id')->toArray();

        return \Thinktomorrow\Chief\Common\TranslatableFields\SelectField::make()
            ->label('Homepagina')
            ->description('Bepaal de homepagina van de site')
            ->options($singles)
            ->selected(chiefSetting('homepage'));
    }
}
