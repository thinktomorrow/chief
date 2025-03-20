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

    public static function setIfNeeded(null|string|int $activeContextId, $model): void
    {
        if ($activeContextId) {
            self::set($activeContextId);

            return;
        }

        // Use the default context if none is set explicitly for this site link
        if ($model instanceof ContextOwner) {
            if ($defaultContextId = app(ContextRepository::class)->getDefaultContextId($model->modelReference())) {
                self::set($defaultContextId);
            }
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
