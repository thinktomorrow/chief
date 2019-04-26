<?php


namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\Manager;

trait StoringAndUpdatingFields
{
    // If there is a save<key>Field this has priority over the set<Key>Field methods
    protected $saveAssistantMethods = [];
    protected $saveMethods = [];

    protected function handleFields(Manager $manager, Request $request)
    {
        foreach ($manager->fieldsWithAssistantFields() as $field) {

            // Custom save methods
            if($this->detectCustomSaveMethods($manager, $field)) continue;

            // Media fields are treated separately
            if ($field->ofType(FieldType::MEDIA, FieldType::DOCUMENT)) {
                if (! isset($this->saveMethods['_files'])) {
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

    protected function detectCustomSaveMethods(Manager $manager, Field $field): bool
    {
        $saveMethodName = 'save'. ucfirst(camel_case($field->key())) . 'Field';

        // Custom save method on assistant
        foreach($manager->assistants() as $assistant)
        {
            if (method_exists($assistant, $saveMethodName)) {
                $this->saveAssistantMethods[$field->key] = ['field' => $field, 'method' => $saveMethodName, 'assistant' => $assistant];
                return true;
            }
        }

        // Custom save method on manager class
        if (method_exists($manager, $saveMethodName)) {
            $this->saveMethods[$field->key] = ['field' => $field, 'method' => $saveMethodName];
            return true;
        }

        return false;
    }

    protected function handleCustomSaves($manager, $request)
    {
        foreach ($this->saveAssistantMethods as $data) {
            $method = $data['method'];
            $data['assistant']->$method($data['field'], $request);
        }

        foreach ($this->saveMethods as $data) {
            $method = $data['method'];
            $manager->$method($data['field'], $request);
        }
    }
}
