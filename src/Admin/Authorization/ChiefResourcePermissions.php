<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\PermissionRegistrar;
use Thinktomorrow\Chief\Resource\PermissionScopedResource;
use Thinktomorrow\Chief\Resource\Resource;

final class ChiefResourcePermissions
{
    public static function guardName(): string
    {
        return config('chief.permissions.guard', 'chief');
    }

    public static function permissionFor(Resource|string $resource, string $ability): string
    {
        $resourceClass = is_string($resource) ? $resource : $resource::class;

        if (! class_exists($resourceClass) || ! is_subclass_of($resourceClass, PermissionScopedResource::class)) {
            return $ability.'-'.$resourceClass;
        }

        return $ability.'-'.$resourceClass::permissionScope();
    }

    /**
     * @return array<int, string>
     */
    public static function abilitiesFor(Resource|string $resource): array
    {
        $resourceClass = is_string($resource) ? $resource : $resource::class;

        if (! class_exists($resourceClass)) {
            return ['view', 'create', 'update', 'delete'];
        }

        return is_subclass_of($resourceClass, PermissionScopedResource::class)
            ? $resourceClass::permissionAbilities()
            : ['view', 'create', 'update', 'delete'];
    }

    /**
     * @return array<int, string>
     */
    public static function permissionsFor(Resource|string $resource): array
    {
        return array_map(
            fn (string $ability): string => self::permissionFor($resource, $ability),
            self::abilitiesFor($resource)
        );
    }

    public static function adminCan(?Authenticatable $admin, string $permission): bool
    {
        if (! $admin) {
            return false;
        }

        if (! self::permissionExists($permission)) {
            self::reportMissing($permission);

            if (config('chief.permissions.strict_missing', false)) {
                throw PermissionDoesNotExist::create($permission, self::guardName());
            }

            return false;
        }

        try {
            return $admin->hasPermissionTo($permission, self::guardName());
        } catch (PermissionDoesNotExist $e) {
            self::reportMissing($permission);

            if (config('chief.permissions.strict_missing', false)) {
                throw $e;
            }

            return false;
        }
    }

    public static function adminCanResource(?Authenticatable $admin, Resource|string $resource, string $ability): bool
    {
        if (! $admin) {
            return false;
        }

        $permission = self::permissionFor($resource, $ability);

        if (! self::permissionExists($permission)) {
            self::reportMissing($permission);

            if (config('chief.permissions.strict_missing', false)) {
                throw PermissionDoesNotExist::create($permission, self::guardName());
            }

            return false;
        }

        try {
            return $admin->hasPermissionTo($permission, self::guardName());
        } catch (PermissionDoesNotExist $e) {
            self::reportMissing($permission);

            if (config('chief.permissions.strict_missing', false)) {
                throw $e;
            }

            return false;
        }
    }

    public static function syncMissingPermissions(iterable $permissions): int
    {
        $created = 0;

        foreach ($permissions as $permission) {
            if (! Permission::where('name', $permission)->where('guard_name', self::guardName())->exists()) {
                Permission::findOrCreate($permission, self::guardName());
                $created++;
            }
        }

        if ($created > 0) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return $created;
    }

    public static function reportMissing(string $permission): void
    {
        if (config('chief.permissions.report_missing', true)) {
            report('Missing Chief permission checked: '.$permission);
        }

        if (! config('chief.permissions.log_missing', true)) {
            return;
        }

        Log::warning('Missing Chief permission checked.', [
            'permission' => $permission,
        ]);
    }

    private static function permissionExists(string $permission): bool
    {
        return Permission::where('name', $permission)
            ->where('guard_name', self::guardName())
            ->exists();
    }
}
