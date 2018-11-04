<?php

namespace Thinktomorrow\Chief\Fields;

class FieldArrangement
{
    /** @var Fields */
    private $fields;

    /** @var array */
    private $tabs;

    public function __construct(Fields $fields, array $tabs = [])
    {
        $this->validateTabs($tabs);
        $this->fillTabsWithTheirFields($tabs, $fields);

        $this->tabs = $tabs;
        $this->fields = $fields;
    }

    public function fields(): array
    {
        return $this->fields->all();
    }

    public function tabs(): array
    {
        return $this->tabs;
    }

    public function hasTabs(): bool
    {
        return count($this->tabs) > 0;
    }

    private function validateTabs(array $tabs)
    {
        array_map(function (FieldTab $tab) {
        }, $tabs);
    }

    private function fillTabsWithTheirFields(array $tabs, Fields $fields)
    {
        foreach ($tabs as $tab) {
            $tab->fill($fields);
        }
    }
}
