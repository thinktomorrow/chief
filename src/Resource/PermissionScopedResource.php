<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Resource;

interface PermissionScopedResource
{
    public static function permissionScope(): string;

    /**
     * @return array<int, string>
     */
    public static function permissionAbilities(): array;
}
