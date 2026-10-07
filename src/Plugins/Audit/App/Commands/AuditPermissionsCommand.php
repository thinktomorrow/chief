<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Commands;

use Illuminate\Console\Command;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;

final class AuditPermissionsCommand extends Command
{
    protected $signature = 'chief-audit:permissions';

    protected $description = 'Create audit permissions and assign them to Chief roles';

    public function handle(): int
    {
        $roles = Role::query()->where('guard_name', ChiefResourcePermissions::guardName())->orderBy('name')->pluck('name')->all();
        $choices = [...$roles, '(geen rollen)'];

        $assignments = [];

        foreach (AuditServiceProvider::PERMISSIONS as $permission) {
            $defaults = $permission === 'view-audit' ? array_intersect(['admin', 'developer'], $roles) : [];
            $selected = $this->choice(
                'Welke rollen krijgen '.$permission.'? (meerdere keuzes gescheiden door komma’s)',
                $choices,
                $defaults ? implode(',', array_keys(array_intersect($choices, $defaults))) : (string) array_key_last($choices),
                null,
                true
            );

            $assignments[$permission] = array_diff($selected, ['(geen rollen)']);
        }

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);

        foreach ($assignments as $permission => $roleNames) {
            foreach ($roleNames as $roleName) {
                $role = Role::findByName($roleName, ChiefResourcePermissions::guardName());

                if (! $role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        }

        $this->info('Auditpermissies aangemaakt en toegekend.');

        return self::SUCCESS;
    }
}
