<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithColumnSelection
{
    public const TREE_BREADCRUMB_COLUMN_KEY = 'tree_breadcrumbs';

    public array $columnSelection = [];

    // Mount and set from session
    public function mountWithColumns(): void
    {
        $this->columnSelection = session()->get(
            $this->getColumnSelectionSessionKey(),
            collect($this->getOptionsForColumnSelection())
                ->filter(fn ($columnSelection) => $columnSelection['selected_by_default'] ?? true)
                ->pluck('key')->toArray(),
        );
    }

    public function updatedColumnSelection(): void
    {
        session()->put($this->getColumnSelectionSessionKey(), $this->columnSelection);
    }

    public function allowColumnSelection(): bool
    {
        return $this->getTable()->isColumnSelectionAllowed();
    }

    private function getColumnSelectionSessionKey(): string
    {
        return 'table.column.selection'.$this->tableReference->toUniqueString();
    }

    public function getColumnSelection(): array
    {
        return $this->columnSelection;
    }

    public function getOptionsForColumnSelection(): array
    {
        // Of all columns, key is the first column item key, value is the header label
        $columns = $this->getTable()->getColumns();
        $options = [];

        foreach ($columns as $i => $column) {

            if (! $firstItem = $column->getItems()[0] ?? null) {
                continue;
            }

            $options[] = [
                'key' => $firstItem->getKey(),
                'label' => $firstItem->getLabel() ?: ucfirst($firstItem->getKey()),
                'disabled' => ! $firstItem->isColumnSelectionAllowed(),
                'selected_by_default' => $firstItem->isColumnSelectedByDefault(),
            ];

            if ($i == 0 && $this->allowsTreeBreadcrumbColumnSelection()) {
                $options[] = [
                    'key' => static::TREE_BREADCRUMB_COLUMN_KEY,
                    'label' => 'Structuur',
                    'disabled' => false,
                    'selected_by_default' => true,
                ];
            }
        }

        return $options;
    }

    public function isTreeBreadcrumbColumnSelected(): bool
    {
        if (! $this->allowColumnSelection()) {
            return true;
        }

        return in_array(static::TREE_BREADCRUMB_COLUMN_KEY, $this->getColumnSelection());
    }
}
