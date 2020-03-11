<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\FlatReferences;

class FlatReferenceFactory
{
    public static function fromString(string $reference): FlatReference
    {
        if (false == strpos($reference, '@')) {
            throw new \InvalidArgumentException('Invalid reference composition. A flat reference should honour schema <class>@<id>. [' . $reference . '] was passed instead.');
        }

        list($className, $id) = explode('@', $reference);

        $instance = app($className);
        $instance->{$instance->getKeyName()} = $id;

        if (!method_exists($instance, 'flatReference')) {
            throw new \InvalidArgumentException('Instance created from model reference [' . $reference . '] was expected to have a method of flatReference() but is has not.');
        }

        return $instance->flatReference();
    }
}
