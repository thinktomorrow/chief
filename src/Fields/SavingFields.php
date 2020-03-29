<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Types\FileField;
use Thinktomorrow\Chief\Fields\Types\ImageField;
use Thinktomorrow\Chief\Media\Application\FileFieldHandler;
use Thinktomorrow\Chief\Media\Application\ImageFieldHandler;

trait SavingFields
{
    // If there is a save<key>Field this has priority over the set<Key>Field methods
    protected $saveAssistantMethods = [];
    protected $saveMethods = [];

    protected $queued_translations = [];

    public function saveCreateFields(Request $request): void
    {
        $this->saveFields($request, $this->createFields());
    }

    public function saveEditFields(Request $request): void
    {
        $this->saveFields($request, $this->editFields());
    }

    protected function saveFields(Request $request, Fields $fields)
    {
        foreach ($fields as $field) {

            // Custom save methods
            if ($this->detectCustomSaveMethods($field)) {
                continue;
            }

            // Custom set methods - default is the generic setField() method.
            $methodName = 'set' . ucfirst(Str::camel($field->getKey())) . 'Field';
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
        $saveMethodByKey = 'save' . ucfirst(Str::camel($field->getKey())) . 'Field';
        $saveMethodByType = 'save' . ucfirst(Str::camel($field->getType()->get())) . 'Fields';

        foreach ([$saveMethodByKey, $saveMethodByType] as $saveMethod) {
            foreach ($this->assistants() as $assistant) {
                if (method_exists($assistant, $saveMethod)) {
                    $this->saveAssistantMethods[$field->getKey()] = [
                        'field'     => $field,
                        'method'    => $saveMethod,
                        'assistant' => $assistant,
                    ];

                    return true;
                }
            }

            if (method_exists($this, $saveMethod)) {
                $this->saveMethods[$field->getKey()] = ['field' => $field, 'method' => $saveMethod];

                return true;
            }
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
            if ($field->ofType(FieldType::FILE, FieldType::IMAGE)) {
                throw new \Exception('Cannot process the ' . $field->getKey() . ' media field. Currently no support for translatable media files. We should fix this!');
            }

            // Okay so this is a bit odd but since all translations are expected to be inside the trans
            // array, we can add all these translations at once. Just make sure to keep track of the
            // keys since this is what our translation engine requires as well for proper update.
            $this->queued_translations = $request->input('trans');
            $this->translation_columns[] = $field->getColumn();

            return;
        }

        // By default we assume the key matches the attribute / column naming
        $this->model->{$field->getColumn()} = $request->input($field->getKey());
    }

    private function saveQueuedFields()
    {
        $queued_translations = $this->queued_translations;

        foreach ($queued_translations as $locale => $translations) {
            foreach ($translations as $key => $value) {
                if (method_exists($this->model, 'isDynamicKey') && $this->model->isDynamicKey($key)) {
                    $this->model->setDynamic($key, $value, $locale);

                    // Remove from queued translations
                    unset($queued_translations[$locale][$key]);
                }

                // remove any empty locale entries
                if (empty($queued_translations[$locale])) {
                    unset($queued_translations[$locale]);
                }
            }
        }

        $this->model->save();

        // Translations
        if (!empty($queued_translations)) {
            $this->saveTranslations($queued_translations, $this->model, $this->translation_columns);
        }

        return (new static($this->registration))->manage($this->model);
    }

    public function saveFileFields(FileField $field, Request $request)
    {
        app(FileFieldHandler::class)->handle($this->model, $field, $request);
    }

    public function saveImageFields(ImageField $field, Request $request)
    {
        app(ImageFieldHandler::class)->handle($this->model, $field, $request);
    }
}
