<?php

namespace Thinktomorrow\Chief\Fields;

use Thinktomorrow\Chief\Fields\Types\Field;

class FieldsTab
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $view;

    /** @var array */
    private $viewData;

    /**
     * array of fieldKeys of the fields which are accepted in this tab
     * @var array
     */
    private $fieldKeys;

    /** @var array */
    private $fields;

    public function __construct(string $title, array $fieldKeys = [], string $view = null, array $viewData = [])
    {
        $this->title = $title;
        $this->fieldKeys = $fieldKeys;
        $this->view = $view;
        $this->viewData = $viewData;

        $this->fields = [];
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * @param Fields $fields
     * @return FieldsTab
     */
    public function fill(Fields $fields): FieldsTab
    {
        foreach ($fields->all() as $field) {
            if ($this->contains($field)) {
                $this->fields[] = $field;
            }
        }

        return $this;
    }

    public function contains(Field $field): bool
    {
        return in_array($field->key, $this->fieldKeys);
    }

    /**
     * Return the fields for this tab.
     *
     * @return array
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * Optional custom view to display this tab and its fields
     * @return string
     */
    public function view(): ?string
    {
        return $this->view;
    }

    public function viewData(): array
    {
        return array_merge(['tab' => $this], $this->viewData);
    }

    public function hasView(): bool
    {
        return !is_null($this->view);
    }
}
