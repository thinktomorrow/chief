<?php

namespace Thinktomorrow\Chief\Fields;

class FieldTab
{
    /** @var array */
    private $fieldKeys;

    /** @var array */
    private $fields;

    /** @var string */
    private $title;

    /** @var string */
    private $view;

    public function __construct(string $title, array $fieldKeys, string $view = null)
    {
        $this->title = $title;
        $this->fieldKeys = $fieldKeys;
        $this->view = $view;
        $this->fields = [];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function fill(Fields $fields): FieldTab
    {
        $result = [];

        foreach ($fields->all() as $field) {
            if (in_array($field->key, $this->fieldKeys)) {
                $result[] = $field;
            }
        }

        $this->fields = $result;

        return $this;
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

    public function hasView(): bool
    {
        return !is_null($this->view);
    }
}
