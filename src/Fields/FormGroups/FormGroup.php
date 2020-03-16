<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\FormGroups;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\AllowsMultiple;
use Thinktomorrow\Chief\Fields\Types\Field;

class FormGroup
{
    use AllowsMultiple;

    /** @var string */
    private $key;

    /** @var Fields */
    private $fields;

    private $label;
    private $description;
    private $view;
    private $viewData;

    private $min;
    private $max;

    private $isRequired = false;

    protected static $defaultView = 'chief::back._formgroups.formgroup';
    protected static $fieldView = 'chief::back._formgroups.fieldgroup';

    private function __construct(Fields $fields)
    {
        $this->fields = $fields;

        $this->view(static::$defaultView);
        $this->viewData([]);

        // Random key to indicate uniqueness when multiple fragments are used
        $this->key = static::generateRandomKey();
    }

    public function getKey()
    {
        return $this->key;
    }

    public static function make(Fields $fields): FormGroup
    {
        return new static($fields);
    }

    public static function fromSingleField(Field $field): FormGroup
    {
        $formGroup = new static(new Fields([$field]));

        if($field->getLabel()) $formGroup->label($field->getLabel());
        if($field->getDescription()) $formGroup->description($field->getDescription());
        $formGroup->markAsRequired($field->required());

        return $formGroup;
    }

    public function fields(): Fields
    {
        // Manipulate each field with a custom view, unless specifically set.
        $this->fields = $this->fields->map(function(Field $field){
            if(!$field->hasCustomView()){
                $field->view(static::$fieldView); // TODO: Field should be immutable but it's not, ...
            }

            return $field;
        });

        return $this->fields;
    }

    public function min(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function max(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function exact(int $exact): self
    {
        $this->min = $this->max = $exact;

        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function markAsRequired(bool $required = true): FormGroup
    {
        $this->isRequired = $required;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * The view path to the full formgroup for this field.
     *
     * @param string $view
     * @return $this|mixed|null|string
     */
    public function view(string $view): FormGroup
    {
        $this->view = $view;

        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function hasCustomView(): bool
    {
        return $this->view !== static::$defaultView;
    }

    public function viewData(array $viewData = []): FormGroup
    {
        $this->viewData = $viewData;

        return $this;
    }

    public function getViewData(): array
    {
        return $this->viewData;
    }

    private static function generateRandomKey(): string
    {
        return md5(time() . mt_rand(1,9999));
    }
}
