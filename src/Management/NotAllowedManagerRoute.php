<?php

namespace Thinktomorrow\Chief\Management;

class NotAllowedManagerRoute extends \Exception
{
    public static function index(ModelManager $manager)
    {
        return static::notAllowedVerb('index', $manager);
    }

    public static function create(ModelManager $manager)
    {
        return static::notAllowedVerb('create', $manager);
    }

    public static function store(ModelManager $manager)
    {
        return static::notAllowedVerb('store', $manager);
    }

    public static function edit(ModelManager $manager)
    {
        return static::notAllowedVerb('edit', $manager);
    }

    public static function update(ModelManager $manager)
    {
        return static::notAllowedVerb('update', $manager);
    }

    public static function delete(ModelManager $manager)
    {
        return static::notAllowedVerb('delete', $manager);
    }

    public static function notAllowedVerb($verb, ModelManager $manager)
    {
        throw new static('Not allowed to '.$verb.' a model. '.ucfirst($verb).' route is not allowed by the ' . $manager->modelDetails()->key.' manager.');
    }

    public static function notAllowedPermission($permission, ModelManager $manager)
    {
        throw new static('Not allowed permission for '.$permission.' on a model as managed by the ' . $manager->modelDetails()->key.' manager.');
    }
}
