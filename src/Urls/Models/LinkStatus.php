<?php

namespace Thinktomorrow\Chief\Urls\Models;

use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

enum LinkStatus: string
{
    case online = 'online';
    case offline = 'offline';

    public static function options(): array
    {
        return [
            self::online->value => 'Online',
            self::offline->value => 'Offline',
        ];
    }

    /**
     * @return array Tuple of [statusLabel, statusVariant]
     */
    public function influenceByModelState($model): array
    {
        if (! $model instanceof StatefulContract) {
            return [$this->value, match ($this) {
                self::online => 'green',
                self::offline => 'grey',
            }];
        }

        return match ($this) {
            self::online => ! $model->inOnlineState() ? ['Online na publicatie', 'orange'] : [$this->value, 'green'],
            self::offline => [$this->value, 'grey'],
        };
    }
}
