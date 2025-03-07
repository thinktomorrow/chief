<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

final class NotAllowedManagerAction extends AuthorizationException
{
    public static function index(string $managerKey)
    {
        return self::notAllowedAction('index', $managerKey);
    }

    public static function create(string $managerKey)
    {
        return self::notAllowedAction('create', $managerKey);
    }

    public static function store(string $managerKey)
    {
        return self::notAllowedAction('store', $managerKey);
    }

    public static function edit(string $managerKey)
    {
        return self::notAllowedAction('edit', $managerKey);
    }

    public static function update(string $managerKey)
    {
        return self::notAllowedAction('update', $managerKey);
    }

    public static function delete(string $managerKey)
    {
        return self::notAllowedAction('delete', $managerKey);
    }

    public static function notAllowedAction(string $action, string $managerKey): self
    {
        return new self('Not allowed to '.$action.' a model. '.ucfirst($action).' route is not allowed by the '.$managerKey.' manager.');
    }

    public static function notAllowedPermission(string $permission, string $managerKey): self
    {
        return new self('Not allowed permission for '.$permission.' on a model as managed by the '.$managerKey.' manager.');
    }
}
