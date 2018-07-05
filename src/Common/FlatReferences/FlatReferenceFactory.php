<?php

namespace Thinktomorrow\Chief\Common\FlatReferences;

class FlatReferenceFactory
{
    public static function fromString(string $reference): FlatReference
    {
        if(false == strpos($reference, '@')) {
            throw new \InvalidArgumentException('Invalid collection id. Composite key should honour schema <class>@<id>. [' . $reference . '] was passed instead.');
        }

        list($className, $id) = explode('@', $reference);

        $instance = new $className();
        $instance->{$instance->getKeyName()} = $id;

        if(! method_exists($instance, 'flatReference')) {
            throw new \InvalidArgumentException('Instance created from model reference [' . $reference . '] was expected to have a method of flatReference() but is has not.');
        }

        return $instance->flatReference();
    }

}