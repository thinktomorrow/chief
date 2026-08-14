<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Resource;

trait PermissionScopedResourceDefault
{
    public static function permissionScope(): string
    {
        return 'page';
    }

    /**
     * @return array<int, string>
     */
    public static function permissionAbilities(): array
    {
        return ['view', 'create', 'update', 'delete'];
    }
}
