<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\FieldValidator;
use Thinktomorrow\Chief\Management\ModelManager;

class UpdateManager
{
    // If there is a save<key>Field this has priority over the set<Key>Field methods
    private $saveMethods = [];

    public function handle(ModelManager $manager, Request $request)
    {
        app(FieldValidator::class)->validate($manager, $request);

        foreach($manager->fields() as $field) {

            // Custom save methods
            $saveMethodName = 'save'. ucfirst(camel_case($field->key())) . 'Field';
            if(method_exists($manager,$saveMethodName)) {
                $this->saveMethods[$field->key] = ['field' => $field, 'method' => $saveMethodName];
                continue;
            }

            // Custom set methods - default is the generic setField() method.
            $methodName = 'set'. ucfirst(camel_case($field->key())) . 'Field';
            (method_exists($manager, $methodName))
                ? $manager->$methodName($field, $request)
                : $manager->setField($field, $request);
        }

        // Save the model
        $manager->saveFields();

        // Handle off any custom save methods
        $this->handleCustomSaves($manager, $request);
    }

    private function handleCustomSaves($manager, $request)
    {
        foreach($this->saveMethods as $data)
        {
            $method = $data['method'];

            $manager->$method($data['field'], $request);
        }
    }
}