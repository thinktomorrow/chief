<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Common\Fields\Field;
use Thinktomorrow\Chief\Common\Fields\FieldType;
use Thinktomorrow\Chief\Management\Fields\FieldArrangement;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

abstract class AbstractManager
{
    use ManagesMedia,
        TranslatableCommand;

    protected $model;

    protected $queued_translations = [];
    protected $translation_columns = [];
    protected $queued_files = [];
    protected $queued_filesOrder = [];

    /** @var Register */
    protected $register;

    /** @var string */
    protected $key;

    public function __construct(Register $register)
    {
        $this->register = $register;
        $this->key = $this->register->filterByClass(static::class)->toKey();

        // Without passing parameter, we assume the generic model instance is set
        $model = $this->register->filterByClass(static::class)->toModel();
        $this->manage(app($model));

        // Check if key and model are present since the model should be set by the manager itself
        $this->validateConstraints();
    }

    public function manage($model): ModelManager
    {
        $this->model = $model;

        return $this;
    }

    public static function findManaged($id): ModelManager
    {
        $model = app(Register::class)->filterByClass(static::class)->toModel();

        return app(static::class)->manage($model::where('id', $id)->first());
    }

    public static function findAllManaged(): Collection
    {
        $model = app(Register::class)->filterByClass(static::class)->toModel();

        return $model::all()->map(function($model){
            return app(static::class)->manage($model);
        });
    }

    public function managerDetails(): ManagerDetails
    {
        return new ManagerDetails(
            $this->key,
            static::class,
            property_exists($this, 'labelSingular') ? $this->labelSingular : str_singular($this->key),
            property_exists($this, 'labelPlural') ? $this->labelPlural : str_plural($this->key)
        );
    }

    /**
     * Information regarding a specific managed model instance.
     *
     * @return ManagedModelDetails
     */
    public function managedModelDetails(): ManagedModelDetails
    {
        // We try to assume a default for title but it's better you set this up yourself.
        $title = $this->model->title ?? ($this->model->id ?? '');
        $locales = method_exists($this->model, 'availableLocales') ? $this->model->availableLocales() : [config('app.locale')];

        return new ManagedModelDetails( $title, '', '', $locales);
    }

    /**
     * Determine which actions should be available for this
     * manager and their respective routed urls.
     *
     * @param $verb
     * @return null|string
     */
    public function route($verb): ?string
    {
        $routes = [
            'index'   => route('chief.back.managers.index', [$this->key]),
            'create'  => route('chief.back.managers.create', [$this->key]),
            'store'   => route('chief.back.managers.store', [$this->key, $this->model->id]),
            'edit'    => route('chief.back.managers.edit', [$this->key, $this->model->id]),
            'update'  => route('chief.back.managers.update', [$this->key, $this->model->id]),
            'delete' => route('chief.back.managers.delete', [$this->key, $this->model->id]),
            'upload' => route('managers.media.upload', [$this->key, $this->model->id])
        ];

        return $routes[$verb] ?? null;
    }

    public function can($verb): bool
    {
        return !is_null($this->route($verb));
    }

    /**
     * This determines the arrangement of the manageable fields
     * on the create and edit forms. By default, all fields
     * are presented in their order of appearance
     */
    public function fieldArrangement(): FieldArrangement
    {
        return new FieldArrangement($this->fields());
    }

    public function getFieldValue($field, $default = null)
    {
        // If string is passed, we use this to find the proper field
        if (is_string($field)) {
            foreach ($this->fields() as $possibleField) {
                if ($possibleField->key() == $field) {
                    $field = $possibleField;
                    break;
                }
            }

            if (is_string($field)) {

                // Could be translatable field
                if($this->isTranslatableKey($field)) {

                    $attribute = substr($field, strrpos($field,'.') + 1);
                    $locale = substr($field, strlen('trans.'), 2);

                    return $this->model->getTranslationFor($attribute, $locale);
                }

                return $default;
            }
        }

        // Is it a media field
        // An array grouped by type is returned. Each media array has an id, filename and path.
        if($field->ofType(FieldType::MEDIA)) {
            return $this->populateMedia($this->model);
        }

        if($field->ofType(FieldType::DOCUMENT)) {
            return $this->populateDocuments($this->model);
        }

        return $this->model->{$field->column()};
    }

    private function isTranslatableKey(string $key): bool
    {
        return 0 === strpos($key, 'trans.');
    }

    public function setField(Field $field, Request $request)
    {
        // Is field set as translatable?
        if ($field->translatable()) {

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

        if ($field->ofType(FieldType::MEDIA, FieldType::DOCUMENT)) {
            // Images are passed as base64 strings, not as file, Documents are passed via the file segment
            $this->queued_files = array_merge($request->get('files', []), $request->file('files', []));
            $this->queued_filesOrder = $request->get('filesOrder', []);

            return;
        }

        // By default we assume the key matches the attribute / column naming
        $this->model->{$field->column()} = $request->get($field->key());
    }

    public function saveFields(): ModelManager
    {
        $this->model->save();

        // Translations
        if (!empty($this->queued_translations)) {
            $this->saveTranslations($this->queued_translations, $this->model, $this->translation_columns);
        }

        // Media
        if (!empty($this->queued_files)) {
            app(UploadMedia::class)->fromUploadComponent($this->model, $this->queued_files, $this->queued_filesOrder);
        }

        return (new static($this->register))->manage($this->model);
    }

    public function delete()
    {
        if( ! $this->can('delete')) {
            NotAllowedManagerRoute::delete($this);
        }

        $this->model->delete();
    }

    public function renderField(Field $field)
    {
        $viewpath ='chief::back._fields.'.$field->type;

        if (!view()->exists($viewpath)) {
            return '';
        }

        $path = $field->translatable()
            ? 'chief::back._fields.translatable'
            : $viewpath;

        return view($path, [
            'field' => $field,
            'key' => $field->key, // As parameter so that it can be altered for translatable values
            'manager' => $this,
            'viewpath' => $viewpath,
        ])->render();
    }

    protected function requestContainsTranslations(Request $request): bool
    {
        return $request->has('trans');
    }

    private function validateConstraints()
    {
        if (!$this->model) {
            throw new \DomainException('Model class should be set for this manager. Please set the model property default via the constructor or by extending the setupDefaults method.');
        }

        if (!$this->key) {
            throw new \DomainException('The registered key identifier should be set for this manager. Please call the provided setupDefaults method in your constructor.');
        }
    }
}