<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers;

interface Manager
{
    public function managedModelClass(): string;

    public function route(string $action, $model = null, ...$parameters): string;

    public function can(string $action, $model = null): bool;
}
