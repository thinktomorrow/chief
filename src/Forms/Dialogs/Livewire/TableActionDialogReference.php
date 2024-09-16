<?php

namespace Thinktomorrow\Chief\Forms\Dialogs\Livewire;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class TableActionDialogReference implements DialogReference
{
    private TableReference $tableReference;
    private string $actionKey;
    private string $modalKey;

    /**
     * Unique Modal reference.
     *
     * This is used to retrieve a specific action
     * modal present in Livewire Table component.
     *
     */
    public function __construct(TableReference $tableReference, string $actionKey, string $modalKey)
    {
        $this->tableReference = $tableReference;
        $this->actionKey = $actionKey;
        $this->modalKey = $modalKey;
    }

    public function toLivewire()
    {
        return [
            'class' => static::class,
            'tableReference' => $this->tableReference->toLivewire(),
            'actionKey' => $this->actionKey,
            'modalKey' => $this->modalKey,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            TableReference::fromLivewire($value['tableReference']),
            $value['actionKey'],
            $value['modalKey']
        );
    }

    public function getDialog(): Dialog
    {
        return $this->tableReference->getTable()->findActionDialog($this->actionKey, $this->modalKey);
    }
}
