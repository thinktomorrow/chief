<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\FieldValidator;
use Thinktomorrow\Chief\Management\ModelManager;

class UpdateManager
{
    public function handle(ModelManager $manager, Request $request)
    {
        app(FieldValidator::class)->validate($manager, $request);

        foreach($manager->fields() as $field) {

            $methodName = 'set'. ucfirst(camel_case($field->key())) . 'Field';

            (method_exists($manager, $methodName))
                ? $manager->$methodName($field, $request)
                : $manager->setField($field, $request);
        }

        $manager->saveFields();
    }
}