<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Types\FileField;
use Thinktomorrow\Chief\Fields\Types\ImageField;
use Thinktomorrow\Chief\Fields\Types\DocumentField;
use Thinktomorrow\Chief\Media\Application\FileFieldHandler;
use Thinktomorrow\Chief\Media\Application\ImageFieldHandler;

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

//            // Media fields are treated separately
//            if ($field->ofType(FieldType::FILE, FieldType::IMAGE, FieldType::DOCUMENT)) {
//                if (! isset($this->saveMethods['_files'])) {
//                    $this->saveMethods['_files'] = ['field' => new Fields([$field]), 'method' => 'uploadMedia'];
//                    continue;
//                }
//
//                $this->saveMethods['_files']['field'][] = $field;
//                continue;
//            }

            // Custom set methods - default is the generic setField() method.
            $methodName = 'set'. ucfirst(Str::camel($field->getKey())) . 'Field';
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
        $saveMethodByKey = 'save'. ucfirst(Str::camel($field->getKey())) . 'Field';
        $saveMethodByType = 'save'. ucfirst(Str::camel($field->getType()->get())) . 'Fields';

        foreach([$saveMethodByKey, $saveMethodByType] as $saveMethod){

            foreach ($this->assistants() as $assistant) {
                if (method_exists($assistant, $saveMethod)) {
                    $this->saveAssistantMethods[$field->getKey()] = ['field' => $field, 'method' => $saveMethod, 'assistant' => $assistant];
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
            if ($field->ofType(FieldType::FILE, FieldType::IMAGE, FieldType::DOCUMENT)) {
                throw new \Exception('Cannot process the ' . $field->getKey() . ' media field. Currently no support for translatable media files. We should fix this!');
            }

            // Okay so this is a bit odd but since all translations are expected to be inside the trans
            // array, we can add all these translations at once. Just make sure to keep track of the
            // keys since this is what our translation engine requires as well for proper update.
            $this->queued_translations = $request->get('trans');
            $this->translation_columns[] = $field->getColumn();

            return;
        }

        // By default we assume the key matches the attribute / column naming
        $this->model->{$field->getColumn()} = $request->get($field->getKey());
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

    public function saveFileFields(FileField $field, Request $request)
    {
        app(FileFieldHandler::class)->handle($this->model, $field, $request);
    }

    public function saveImageFields(ImageField $field, Request $request)
    {
        app(ImageFieldHandler::class)->handle($this->model, $field, $request);
    }

    public function saveDocumentFields(DocumentField $field, Request $request)
    {
        app(FileFieldHandler::class)->handle($this->model, $field, $request);
    }
}
