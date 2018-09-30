<?php

namespace Thinktomorrow\Chief\Management\Fields;

class FieldTab
{
    /** @var array */
    private $fieldKeys;

    /** @var array */
    private $fields;

    /** @var string */
    private $title;

    public function __construct(string $title, array $fieldKeys)
    {
        $this->title = $title;
        $this->fieldKeys = $fieldKeys;
        $this->fields = [];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function fill(array $fields): FieldTab
    {
        $result = [];

        foreach($fields as $field) {
            if(in_array($field->key, $this->fieldKeys)) {
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
}