<?php

namespace Thinktomorrow\Chief\Table\Filters\Presets;

use Thinktomorrow\Chief\Table\Filters\ButtonGroupFilter;

class OnlineStateFilter extends ButtonGroupFilter
{
    public static function makeDefault(string $key = 'current_state'): self
    {
        return static::make($key)
            ->label('Status')
            ->options([
                '' => 'Alle',
                'published' => 'Online',
                'draft' => 'Offline',
            ])->value('');
    }

    public static function makeSimpleStateDefault(string $key = 'current_state'): self
    {
        return static::make($key)
            ->label('Status')
            ->options([
                '' => 'Alle',
                'online' => 'Online',
                'offline' => 'Offline',
            ])->value('');
    }
}
