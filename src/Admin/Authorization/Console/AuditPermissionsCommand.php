<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization\Console;

use Illuminate\Console\Command;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PermissionScopedResource;

final class AuditPermissionsCommand extends Command
{
    protected $signature = 'chief:permissions:audit
                                {--sync : Create missing permissions for registered resources.}';

    protected $description = 'Audit Chief resource permissions';

    public function handle(Registry $registry): int
    {
        $expectedPermissions = $this->expectedPermissions($registry);
        $existingPermissions = Permission::query()
            ->where('guard_name', ChiefResourcePermissions::guardName())
            ->pluck('name')
            ->all();

        $missingPermissions = array_values(array_diff($expectedPermissions, $existingPermissions));
        $unusedPermissions = array_values(array_diff($existingPermissions, $expectedPermissions));

        if ($this->option('sync')) {
            $created = ChiefResourcePermissions::syncMissingPermissions($missingPermissions);
            $this->info($created.' missing permission(s) created.');

            $existingPermissions = Permission::query()
                ->where('guard_name', ChiefResourcePermissions::guardName())
                ->pluck('name')
                ->all();
            $missingPermissions = array_values(array_diff($expectedPermissions, $existingPermissions));
            $unusedPermissions = array_values(array_diff($existingPermissions, $expectedPermissions));
        }

        $this->line('Expected resource permissions: '.count($expectedPermissions));

        $this->reportPermissions('Missing permissions', $missingPermissions, 'warn');
        $this->reportPermissions('Unused permissions', $unusedPermissions, 'line');

        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function expectedPermissions(Registry $registry): array
    {
        $permissions = [];

        foreach ($registry->resources() as $resource) {
            if (! $resource instanceof PermissionScopedResource) {
                continue;
            }

            $permissions = array_merge($permissions, ChiefResourcePermissions::permissionsFor($resource));
        }

        sort($permissions);

        return array_values(array_unique($permissions));
    }

    /**
     * @param  array<int, string>  $permissions
     */
    private function reportPermissions(string $title, array $permissions, string $method): void
    {
        $this->line('');
        $this->line($title.': '.count($permissions));

        foreach ($permissions as $permission) {
            $this->{$method}('- '.$permission);
        }
    }
}
