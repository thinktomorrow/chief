<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\FieldName;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationNames;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;

abstract class AbstractField
{
    use AllowsTags;
    use AllowsConditionalFields;

    /** @var string */
    protected $key;

    /** @var string */
    protected $column;

    /** @var string */
    protected $name;

    /** @var string */
    protected $label;

    /** @var null|string */
    protected $description;

    /** @var null|array|string */
    protected $placeholder;

    /** @var null|array|string */
    protected $prepend;

    /** @var null|array|string */
    protected $append;

    /** @var null|mixed */
    protected $value;

    /** @var null|mixed */
    protected $default;

    /** @var null|mixed */
    protected $model;

    protected string $view;
    protected string $windowView;
    protected array $viewData = [];
    protected array $locales = [];
    protected ValidationParameters $validation;

    protected \Closure $valueResolver;
    protected \Closure $sanitizationResolver;
    private ?\Closure $setResolver = null;
    private ?\Closure $saveResolver = null;

    protected string $localizedFormat = 'trans.:locale.:name';

    private array $fieldNamePlaceholders = [];

    /** @var FieldType */
    private $type;
    private ?string $customSaveMethod = null;

    private array $whenModelIsSetCallbacks = [];

    final public function __construct(FieldType $type, string $key)
    {
        $this->type = $type;
        $this->key = $this->column = $this->name = $key;
        $this->label = null;

        $this->validation([]);

        $this->valueResolver($this->defaultEloquentValueResolver());
        $this->sanitize(fn ($value) => $value);
    }

    public function placeholders(string $placeholder, $value): Field
    {
        $this->fieldNamePlaceholders[$placeholder] = $value;

        return $this;
    }

    public function getType(): FieldType
    {
        return $this->type;
    }

    public function ofType(...$type): bool
    {
        return $this->type->equalsAny($type);
    }

    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function name(string $name): Field
    {
        $this->name = $name;

        // Trigger the locales method again so we have a proper reference of the default validation name property.
        $this->locales($this->locales);

        return $this;
    }

    public function getName(string $locale = null): string
    {
        return $this->fieldName()
            ->withBrackets()
            ->get($locale)
        ;
    }

    public function getId(string $locale = null): string
    {
        return $this->getDottedName($locale);
    }

    public function getDottedName(string $locale = null): string
    {
        return $this->fieldName()->get($locale);
    }

    public function localizedFormat(string $format): Field
    {
        $this->localizedFormat = $format;

        return $this;
    }

    public function column(string $column): Field
    {
        $this->column = $column;

        return $this;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function label(string $label): Field
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function description(string $description): Field
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param mixed $prepend
     */
    public function prepend($prepend): Field
    {
        $this->prepend = $prepend;

        return $this;
    }

    public function getPrepend(?string $locale = null): ?string
    {
        return $this->extractLocalizedItem($this->prepend, $locale);
    }

    /**
     * @param mixed $append
     */
    public function append($append): Field
    {
        $this->append = $append;

        return $this;
    }

    public function getAppend(?string $locale = null): ?string
    {
        return $this->extractLocalizedItem($this->append, $locale);
    }

    /**
     * @param mixed $placeholder
     */
    public function placeholder($placeholder): Field
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(?string $locale = null): ?string
    {
        return $this->extractLocalizedItem($this->placeholder, $locale);
    }

    /**
     * This value will always be returned as value. If you want to provide a default value in case
     * the model record does not have a value provided, use the default() method instead.
     *
     * @param $value
     */
    public function value($value): Field
    {
        $this->value = $value;

        return $this;
    }

    /**
     * A default value in case the model does not provide the value itself.
     *
     * @param $default
     */
    public function default($default): Field
    {
        $this->default = $default;

        return $this;
    }

    public function getValue(?string $locale = null)
    {
        return call_user_func_array($this->valueResolver, [$this->getModel(), $locale, $this]);
    }

    public function valueResolver(\Closure $fn): Field
    {
        $this->valueResolver = $fn;

        return $this;
    }

    public function customSave(\Closure $fn): Field
    {
        $this->saveResolver = $fn;

        return $this;
    }

    public function hasCustomSave(): bool
    {
        return ! ! $this->saveResolver;
    }

    public function handleCustomSave($field, $input, $files)
    {
        return call_user_func_array($this->saveResolver, [$field, $input, $files]);
    }

    public function customSet(\Closure $fn): Field
    {
        $this->setResolver = $fn;

        return $this;
    }

    public function hasCustomSet(): bool
    {
        return ! ! $this->setResolver;
    }

    public function handleCustomSet($field, $input, $files, $locale = null)
    {
        return call_user_func_array($this->setResolver, [$field, $input, $files, $locale]);
    }

    public function getCustomSaveMethod(): ?string
    {
        return $this->customSaveMethod;
    }

    public function customSaveMethod(string $method): Field
    {
        $this->customSaveMethod = $method;

        return $this;
    }

    public function getSanitizedValue($value, array $input = [], ?string $locale = null)
    {
        return call_user_func_array($this->sanitizationResolver, [$value, $input, $locale]);
    }

    public function sanitize(\Closure $fn): Field
    {
        $this->sanitizationResolver = $fn;

        return $this;
    }

    /**
     * @param mixed $rules
     */
    public function validation($rules, array $messages = [], array $attributes = []): Field
    {
        $this->validation = new ValidationParameters($rules, $messages, $attributes ?: [$this->getLabel()]);

        return $this;
    }

    public function getValidationParameters(): ValidationParameters
    {
        return $this->validation;
    }

    public function hasValidation(): bool
    {
        return isset($this->validation) && ! $this->validation->isEmpty();
    }

    public function required(): bool
    {
        if (! $this->hasValidation()) {
            return false;
        }

        if ($this->getValidationParameters() instanceof ValidationParameters) {
            foreach ($this->getValidationParameters()->getRules() as $rule) {
                if (is_string($rule) && false !== strpos($rule, 'required')) {
                    return true;
                }
            }
        }

        return false;
    }

//    public function optional(): bool
//    {
//        return ! $this->required();
//    }

    public function getValidationNames(array $payload = []): array
    {
        return ValidationNames::fromFormat($this->getValidationNameFormat())
            ->payload($payload)
            ->replace('locale', $this->getLocales())
            ->replace('name', [$this->getName()])
            ->removeKeysContaining(['files.*.detach', 'images.*.detach'])
            ->get()
        ;
    }

    public function locales(array $locales = null): Field
    {
        $this->locales = (null === $locales)
            ? config('chief.locales', [])
            : $locales;

        return $this;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function isLocalized(): bool
    {
        return count($this->locales) > 0;
    }

    /**
     * @deprecated use locales(array $locales) instead
     */
    public function translatable(array $locales): Field
    {
        return $this->locales($locales);
    }

    /**
     * @deprecated use isLocalized() instead
     */
    public function isTranslatable(): bool
    {
        return $this->isLocalized();
    }

    public function render(array $viewData = []): string
    {
        return view($this->getView(), array_merge($viewData, $this->getViewData()))->render();
    }

    /**
     * @deprecated use renderOnPage instead
     * @param array $viewData
     * @return string
     */
    public function renderWindow(array $viewData = []): string
    {
        throw new \Exception('Field->renderWindow is no longer being used. Use Field->renderOnPage instead');
    }

    public function renderOnPage(array $viewData = []): string
    {
        return view($this->getWindowView(), array_merge($viewData, $this->getViewData()))->render();
    }

    /**
     * @param mixed $model
     */
    public function model($model): Field
    {
        $this->model = $model;

        foreach ($this->whenModelIsSetCallbacks as $callback) {
            call_user_func_array($callback, [$model]);
        }

        return $this;
    }

    public function whenModelIsSet(\Closure $callback): Field
    {
        $this->whenModelIsSetCallbacks[] = $callback;

        return $this;
    }

    /**
     * The view path to the full formgroup for this field.
     */
    public function view(string $view): Field
    {
        $this->view = $view;

        return $this;
    }

    /**
     * The view path to the full formgroup for this field.
     */
    public function windowView(string $windowView): Field
    {
        $this->windowView = $windowView;

        return $this;
    }

    public function getViewKey(): string
    {
        return $this->type->get();
    }

    public function viewData(array $viewData = []): Field
    {
        $this->viewData = array_merge($this->viewData, $viewData);

        return $this;
    }

    public function notOnCreate(): Field
    {
        $this->tag('not-on-create');

        return $this;
    }

    /**
     * @deprecated use Field->tag('pagetitle') instead
     * @return Field
     * @throws \Exception
     */
    public function editAsPageTitle(): Field
    {
        throw new \Exception('editAsPagetitle is no longer being used. Please replace your method with Field->tag(\'pagetitle\') instead.');
    }

    protected function getValidationNameFormat(): string
    {
        if ($this->isLocalized()) {
            return $this->getLocalizedNameFormat();
        }

        return $this->fieldName()->get();
    }

    protected function getLocalizedNameFormat(): string
    {
        if (false !== strpos($this->name, ':locale')) {
            return $this->name;
        }

        return $this->localizedFormat;
    }

    protected function fieldName(): FieldName
    {
        return FieldName::fromString($this->name)
            ->placeholders($this->fieldNamePlaceholders)
            ->localizedFormat($this->getLocalizedNameFormat())
        ;
    }

    /**
     * @param null|array|string $items
     */
    protected function extractLocalizedItem($items, ?string $locale = null): ?string
    {
        if (! is_array($items)) {
            return $items;
        }

        if ($locale && isset($items[$locale])) {
            return $items[$locale];
        }

        return reset($items);
    }

    protected function getModel()
    {
        return $this->model;
    }

    protected function getView(): string
    {
        if (isset($this->view)) {
            return $this->view;
        }

        return $this->isLocalized()
            ? 'chief::manager.fields.form.localized'
            : 'chief::manager.fields.form.types.'.$this->getViewKey();
    }

    protected function getViewData(): array
    {
        return array_merge([
            'model' => $this->getModel(),
            'field' => $this,
            'key' => $this->getKey(),
        ], $this->viewData);
    }

    // TODO: rename to getShowView() / getEditView
    protected function getWindowView(): string
    {
        if (isset($this->windowView)) {
            return $this->windowView;
        }

        return 'chief::manager.fields.window.types.'.$this->getViewKey();
    }

    /**
     * Default value retrieval. If the model has the property, this value will be used.
     * Otherwise the passed value will be used instead.
     */
    private function defaultEloquentValueResolver(): \Closure
    {
        return function ($model = null, $locale = null) {
            if ($this->value) {
                return $this->value;
            }

            if (! $model) {
                return $this->default;
            }

            if ($locale && $this->isLocalized()) {
                if (method_exists($model, 'isDynamic') && $model->isDynamic($this->getColumn())) {
                    return $model->dynamic($this->getColumn(), $locale);
                }

                // Astrotomic translatable
                return $model->{$this->getColumn().':'.$locale};
            }

            return $model->{$this->getColumn()} ?: $this->default;
        };
    }
}