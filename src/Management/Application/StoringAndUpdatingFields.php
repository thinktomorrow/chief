<?php


namespace Thinktomorrow\Chief\Management\Application;


use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\ModelManager;

trait StoringAndUpdatingFields
{
    // If there is a save<key>Field this has priority over the set<Key>Field methods
    protected $saveMethods = [];

    protected function handleFields(ModelManager $manager, Request $request)
    {
        foreach($manager->fields() as $field) {

            // Custom save methods
            $saveMethodName = 'save'. ucfirst(camel_case($field->key())) . 'Field';
            if(method_exists($manager,$saveMethodName)) {
                $this->saveMethods[$field->key] = ['field' => $field, 'method' => $saveMethodName];
                continue;
            }

            // Media fields are treated separately
            if($field->ofType(FieldType::MEDIA, FieldType::DOCUMENT)) {

                if( ! isset($this->saveMethods['_files'])){
                    $this->saveMethods['_files'] = ['field' => new Fields([$field]), 'method' => 'uploadMedia'];
                    continue;
                }

                $this->saveMethods['_files']['field'][] = $field;
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
    }

    protected function handleCustomSaves($manager, $request)
    {
        foreach($this->saveMethods as $data)
        {
            $method = $data['method'];

            $manager->$method($data['field'], $request);
        }
    }
}