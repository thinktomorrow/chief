<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\FieldValidator;
use Thinktomorrow\Chief\Management\ModelManager;

class StoreManager
{
    public function handle(ModelManager $manager, Request $request): ModelManager
    {
        app(FieldValidator::class)->validate($manager, $request);

        foreach($manager->fields() as $field) {

            $methodName = 'set'. ucfirst(camel_case($field->key())) . 'Field';

            (method_exists($manager, $methodName))
                ? $manager->$methodName($field, $request)
                : $manager->setField($field, $request);
        }

        // Since the model doesn't exist yet, it is now created via the save method
        // For the store we return the new manager which is now connected to the created model instance
        return $manager->saveFields();
    }
}