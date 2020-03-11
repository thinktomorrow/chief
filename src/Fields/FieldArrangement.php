<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields;

class FieldArrangement
{
    /** @var Fields */
    private $fields;

    /** @var array */
    private $tabs;

    final public function __construct(Fields $fields, array $tabs = [])
    {
        $this->fields = $fields;

        $this->validateTabs($tabs);
        $this->tabs = $tabs;

        $this->fillTabsWithTheirFields();
    }

    public function addTab(FieldsTab $tab, $order = null)
    {
        $order = (null === $order) ? count($this->tabs) : $order;

        $tabs = $this->tabs;
        array_splice($tabs, $order, 0, [$tab]);

        return new static($this->fields, $tabs);
    }

    public function fields(): Fields
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
        array_map(function (FieldsTab $tab) {
        }, $tabs);
    }

    private function fillTabsWithTheirFields()
    {
        // Clone the fields
        $remainingFields = new Fields($this->fields->all());
        $remainingFieldsTabIndex = null;

        foreach ($this->tabs as $index => $tab) {

            // Take note of a wildcard tab
            if ($tab instanceof RemainingFieldsTab) {
                $remainingFieldsTabIndex = $index;
                continue;
            }

            // Slim down the remaining fields array so in the end we know which fields are actually missing/
            foreach ($remainingFields as $k => $remainingField) {
                if ($tab->contains($remainingField)) {
                    unset($remainingFields[$k]);
                }
            }

            $tab->fill($this->fields);
        }

        if ($remainingFieldsTabIndex) {
            $this->tabs[$remainingFieldsTabIndex] = $this->tabs[$remainingFieldsTabIndex]->withRemaining($remainingFields->keys())->fill($this->fields);
        }
    }
}
