<?php

namespace Thinktomorrow\Chief\Management\Fields;

class FieldArrangement
{
    /** @var array */
    private $fields;

    /** @var array */
    private $tabs;

    public function __construct(array $fields, array $tabs = [])
    {
        $this->validateTabs($tabs);
        $this->fillTabsWithTheirFields($tabs, $fields);

        $this->tabs = $tabs;
        $this->fields = $fields;
    }

    public function fields(): array
    {
        return $this->fields;
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
        array_map(function(FieldTab $tab){}, $tabs);
    }

    private function fillTabsWithTheirFields(array $tabs, array $allFields)
    {
        foreach($tabs as $tab){
            $tab->fill($allFields);
        }
    }
}