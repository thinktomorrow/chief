<?php

namespace Thinktomorrow\Chief\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Management\Manager;

trait SavingFields
{
    // If there is a save<key>Field this has priority over the set<Key>Field methods
    protected $saveAssistantMethods = [];
    protected $saveMethods = [];

    protected $queued_translations = [];

    public function saveFields(Request $request)
    {
        foreach ($this->fieldsWithAssistantFields() as $field) {

            // Custom save methods
            if ($this->detectCustomSaveMethods($field)) {
                continue;
            }

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
            $methodName = 'set'. ucfirst(Str::camel($field->key())) . 'Field';
            (method_exists($this, $methodName))
                ? $this->$methodName($field, $request)
                : $this->setField($field, $request);
        }

        // Save the model
        $this->saveQueuedFields();

        $this->saveQueuedMethods($request);

        // Attach the updated model to our manager.
        $this->manage($this->model);
    }

    protected function detectCustomSaveMethods(Field $field): bool
    {
        $saveMethodName = 'save'. ucfirst(Str::camel($field->key())) . 'Field';

        // Custom save method on assistant
        foreach ($this->assistants() as $assistant) {
            if (method_exists($assistant, $saveMethodName)) {
                $this->saveAssistantMethods[$field->key()] = ['field' => $field, 'method' => $saveMethodName, 'assistant' => $assistant];
                return true;
            }
        }

        // Custom save method on manager class
        if (method_exists($this, $saveMethodName)) {
            $this->saveMethods[$field->key()] = ['field' => $field, 'method' => $saveMethodName];
            return true;
        }

        return false;
    }

    private function saveQueuedMethods($request)
    {
        foreach ($this->saveAssistantMethods as $data) {
            $method = $data['method'];
            $data['assistant']->$method($data['field'], $request);
        }

        foreach ($this->saveMethods as $data) {
            $method = $data['method'];
            $this->$method($data['field'], $request);
        }
    }

    public function setField(Field $field, Request $request)
    {
        // Is field set as translatable?
        if ($field->isTranslatable()) {
            if (!$this->requestContainsTranslations($request)) {
                return;
            }

            // Make our media fields able to be translatable as well...
            if ($field->ofType(FieldType::MEDIA, FieldType::DOCUMENT)) {
                throw new \Exception('Cannot process the ' . $field->key . ' media field. Currently no support for translatable media files. We should fix this!');
            }

            // Okay so this is a bit odd but since all translations are expected to be inside the trans
            // array, we can add all these translations at once. Just make sure to keep track of the
            // keys since this is what our translation engine requires as well for proper update.
            $this->queued_translations = $request->get('trans');
            $this->translation_columns[] = $field->column();

            return;
        }

        // By default we assume the key matches the attribute / column naming
        $this->model->{$field->column()} = $request->get($field->key());
    }

    private function saveQueuedFields()
    {
        $this->model->save();

        // Translations
        if (!empty($this->queued_translations)) {
            $this->saveTranslations($this->queued_translations, $this->model, $this->translation_columns);
        }

        return (new static($this->registration))->manage($this->model);
    }
}