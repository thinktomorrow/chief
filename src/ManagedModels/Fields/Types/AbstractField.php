<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldName;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\ValidationNames;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\ValidationParameters;

abstract class AbstractField
{
    use AllowsTags;
    use AllowsComponentTags;
    use AllowsToggle;

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

    /** @var null|mixed */
    protected $default = null;

    /** @var callable */
    protected $valueResolver;

    /** @var null|mixed */
    protected $model = null;

    protected string $view;
    protected array $viewData = [];
    protected array $locales = [];
    protected ValidationParameters $validation;

    protected string $localizedFormat = 'trans.:locale.:name';

    final public function __construct(FieldType $type, string $key)
    {
        $this->type = $type;
        $this->key = $this->column = $this->name = $key;
        $this->label = str_replace('_', ' ', $key);

        $this->validation([]);

        $this->valueResolver($this->defaultEloquentValueResolver());
        $this->sanitize(fn ($value) => $value);
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

    /**
     * @return Field
     */
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

    public function getId(string $locale = null): string
    {
        return $this->getDottedName($locale);
    }

    public function getDottedName(string $locale = null): string
    {
        return $this->fieldName()->get($locale);
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

    /**
     * @return Field
     */
    public function localizedFormat(string $format): Field
    {
        $this->localizedFormat = $format;

        return $this;
    }

    protected function fieldName(): FieldName
    {
        return FieldName::fromString($this->name)
            ->localizedFormat($this->getLocalizedNameFormat());
    }

    /**
     * @return Field
     */
    public function column(string $column): Field
    {
        $this->column = $column;

        return $this;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return Field
     */
    public function label(string $label): Field
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return Field
     */
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
     * @return Field
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
     * @return Field
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
     * @return Field
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
     *
     * @return Field
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
     *
     * @return Field
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

    /**
     * @return Field
     */
    public function valueResolver(\Closure $fn): Field
    {
        $this->valueResolver = $fn;

        return $this;
    }

    public function getSanitizedValue($value, array $input = [], ?string $locale = null)
    {
        return call_user_func_array($this->sanitizationResolver, [$value, $input, $locale]);
    }

    /**
     * @return Field
     */
    public function sanitize(\Closure $fn): Field
    {
        $this->sanitizationResolver = $fn;

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
                return $model->{$this->getColumn() . ':' . $locale};
            }

            return $model->{$this->getColumn()} ?: $this->default;
        };
    }

    /**
     * @return Field
     */
    public function validation($rules, array $messages = [], array $attributes = []): Field
    {
        $this->validation = new ValidationParameters($rules, $messages, $attributes ?: [$this->getLabel()]);

        return $this;
    }

    /**
     * @return ValidationParameters
     */
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

    public function optional(): bool
    {
        return ! $this->required();
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
     * @return Field
     */
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
     * @return bool
     * @deprecated use isLocalized() instead
     */
    public function isTranslatable(): bool
    {
        return $this->isLocalized();
    }

    /**
     * @param array|null|string $items
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

    public function render(array $viewData = []): string
    {
        return view($this->getView(), array_merge($viewData, $this->getViewData()))->render();
    }

    /**
     * @return Field
     */
    public function model($model): Field
    {
        $this->model = $model;

        return $this;
    }

    protected function getModel()
    {
        return $this->model;
    }

    /**
     * The view path to the full formgroup for this field.
     *
     * @param string $view
     *
     * @return Field
     */
    public function view(string $view): Field
    {
        $this->view = $view;

        return $this;
    }

    protected function getView(): string
    {
        if (isset($this->view)) {
            return $this->view;
        }

        return $this->isLocalized()
            ? 'chief::manager.cards.fields.formgroup_translatable'
            : 'chief::manager.fieldtypes.' . $this->getViewKey();
    }

    public function getViewKey(): string
    {
        return $this->type->get();
    }

    /**
     * @return Field
     */
    public function viewData(array $viewData = []): Field
    {
        $this->viewData = array_merge($this->viewData, $viewData);

        return $this;
    }

    protected function getViewData(): array
    {
        return array_merge([
            'model' => $this->getModel(),
            'field' => $this,
            'key' => $this->getKey(),
        ], $this->viewData);
    }

    public function notOnCreate(): Field
    {
        $this->tag('not-on-create');

        return $this;
    }

    public function editAsPageTitle(): Field
    {
        $this->tag('chief-component');
        $this->component('chief-page-title');

        return $this;
    }
}
