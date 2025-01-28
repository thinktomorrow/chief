<?php

namespace Thinktomorrow\Chief\Table\Filters\Presets;

use Thinktomorrow\Chief\Table\Filters\ButtonGroupFilter;

class OnlineStateFilter extends ButtonGroupFilter
{
    public static function makeDefault(): self
    {
        return static::make('current_state')
            ->label('Status')
            ->options([
                '' => 'Alle',
                'published' => 'Online',
                'draft' => 'Offline',
            ])->value('');
    }

    public static function makeSimpleStateDefault(): self
    {
        return static::make('current_state')
            ->label('Status')
            ->options([
                '' => 'Alle',
                'online' => 'Online',
                'offline' => 'Offline',
            ])->value('');
    }
}
