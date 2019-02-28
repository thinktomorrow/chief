<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Auth\Access\AuthorizationException;

class NotAllowedManagerRoute extends AuthorizationException
{
    public static function index(Manager $manager)
    {
        return static::notAllowedVerb('index', $manager);
    }

    public static function create(Manager $manager)
    {
        return static::notAllowedVerb('create', $manager);
    }

    public static function store(Manager $manager)
    {
        return static::notAllowedVerb('store', $manager);
    }

    public static function edit(Manager $manager)
    {
        return static::notAllowedVerb('edit', $manager);
    }

    public static function update(Manager $manager)
    {
        return static::notAllowedVerb('update', $manager);
    }

    public static function delete(Manager $manager)
    {
        return static::notAllowedVerb('delete', $manager);
    }

    public static function notAllowedVerb($verb, Manager $manager)
    {
        throw new static('Not allowed to '.$verb.' a model. '.ucfirst($verb).' route is not allowed by the ' . $manager->details()->key.' manager.');
    }

    public static function notAllowedPermission($permission, Manager $manager)
    {
        throw new static('Not allowed permission for '.$permission.' on a model as managed by the ' . $manager->details()->key.' manager.');
    }
}
