<?php

namespace Thinktomorrow\Chief\Fragments;

use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;

class ActiveContextId
{
    private static ?string $activeContextId = null;

    public static function set(string|int $activeContextId): void
    {
        self::$activeContextId = (string) $activeContextId;
    }

    public static function setForSite(string $site, $model): void
    {
        if (! $model instanceof ContextOwner) {
            return;
        }

        if ($contextId = app(ContextRepository::class)->guessContextIdForSite($model->modelReference(), $site)) {
            self::set($contextId);
        }
    }

    public static function exists(): bool
    {
        return isset(self::$activeContextId);
    }

    public static function get(): string
    {
        if (! self::exists()) {
            throw new \InvalidArgumentException('No active context id set.');
        }

        return self::$activeContextId;
    }

    public static function clear(): void
    {
        self::$activeContextId = null;
    }
}
