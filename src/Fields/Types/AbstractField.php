<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\Chief\Fields\FieldName;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fields\Validation\ValidationNames;
use Thinktomorrow\Chief\Fields\Validation\ValidationParameters;

abstract class AbstractField
{
    /** @var FieldType */
    private $type;

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

    /** @var null|string|array */
    protected $placeholder;

    /** @var null|string|array */
    protected $prepend;

    /** @var null|string|array */
    protected $append;

    /** @var null|mixed */
    protected $value;

    /** @var callable */
    protected $valueResolver;

    /** @var string */
    protected $view;

    /** @var string|null */
    protected $elementView;

    /** @var array */
    protected $viewData;

    /** @var array */
    protected $locales;

    /** @var null|Validator|array|\Closure */
    protected $validation;

    final public function __construct(FieldType $type, string $key)
    {
        $this->type = $type;
        $this->key = $this->column = $this->name = $this->label = $key;

        $this->view('chief::back._fields.formgroup');
        $this->viewData([]);
        $this->locales([]);

        $this->valueResolver($this->defaultEloquentValueResolver());
    }

    public function getType(): FieldType
    {
        return $this->type;
    }

    public function ofType(...$type): bool
    {
        return $this->type->equalsAny($type);
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
            ->get($locale);
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

        return 'trans.:locale.:name';
    }

    protected function fieldName(): FieldName
    {
        return FieldName::fromString($this->name)
            ->localizedFormat($this->getLocalizedNameFormat());
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

    public function prepend($prepend): Field
    {
        $this->prepend = $prepend;

        return $this;
    }

    public function getPrepend(?string $locale = null): ?string
    {
        return $this->extractLocalizedItem($this->prepend, $locale);
    }

    public function append($append): Field
    {
        $this->append = $append;

        return $this;
    }

    public function getAppend(?string $locale = null): ?string
    {
        return $this->extractLocalizedItem($this->append, $locale);
    }

    public function placeholder($placeholder): Field
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(?string $locale = null): ?string
    {
        return $this->extractLocalizedItem($this->placeholder, $locale);
    }

    public function value($value): Field
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param $default
     * @return $this
     * @deprecated use value() instead
     */
    public function default($default)
    {
        $this->default = $default;

        return $this;
    }

    public function getValue($model = null, ?string $locale = null)
    {
        return call_user_func_array($this->valueResolver, [$model, $locale]);
    }

    public function valueResolver(\Closure $fn): Field
    {
        $this->valueResolver = $fn;

        return $this;
    }

    /**
     * Default value retrieval. If the model has the property, this value will be used.
     * Otherwise the passed value will be used instead.
     *
     * @return \Closure
     */
    private function defaultEloquentValueResolver(): \Closure
    {
        return function (Model $model = null, $locale = null) {
            if (!$model) {
                return $this->value;
            }

            if ($locale && $this->isLocalized()) {
                return $model->getTranslationFor($this->getColumn(), $locale);
            }

            return $model->{$this->getColumn()} ?: $this->value;
        };
    }

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
        return isset($this->validation);
    }

    public function required(): bool
    {
        if (!$this->hasValidation()) {
            return false;
        }

        if ($this->getValidationParameters() instanceof ValidationParameters) {
            foreach ($this->getValidationParameters()->getRules() as $rule) {
                if (false !== strpos($rule, 'required')) {
                    return true;
                }
            }
        }

        return false;
    }

    public function optional(): bool
    {
        return !$this->required();
    }

    public function getValidationNames(array $payload = []): array
    {
        return ValidationNames::fromFormat($this->getValidationNameFormat())
            ->payload($payload)
            ->replace('locale', $this->getLocales())
            ->replace('name', [$this->getName()])
            ->removeKeysContaining(['files.*.detach', 'images.*.detach'])
            ->get();
    }

    /**
     * @deprecated use locales(array $locales) instead
     */
    public function translatable(array $locales): Field
    {
        return $this->locales($locales);
    }

    /**
     * @return bool
     * @deprecated use isLocalized() instead
     */
    public function isTranslatable(): bool
    {
        return $this->isLocalized();
    }

    public function locales(array $locales): Field
    {
        $this->locales = $locales;

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

    protected function extractLocalizedItem($items, ?string $locale = null): ?string
    {
        if (!is_array($items)) {
            return $items;
        }

        if ($locale && isset($items[$locale])) {
            return $items[$locale];
        }

        return reset($items);
    }

    /**
     * The view path to the full formgroup for this field.
     *
     * @param string $view
     * @return $this|mixed|null|string
     */
    public function view(string $view): Field
    {
        $this->view = $view;

        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function viewData(array $viewData = []): Field
    {
        $this->viewData = $viewData;

        return $this;
    }

    public function getViewData(): array
    {
        return $this->viewData;
    }

    /**
     * In case of the default formgroup rendering, there is also made use of
     * the form input element, which is targeted as a specific view as well
     *
     * @param string $view
     * @return Field
     */
    public function elementView(string $view): Field
    {
        $this->elementView = $view;

        return $this;
    }

    public function getElementView(): string
    {
        if ($this->elementView) {
            return $this->elementView;
        }

        return $this->isLocalized()
            ? 'chief::back._fields.translatable'
            : 'chief::back._fields.' . $this->type->get();
    }
}
