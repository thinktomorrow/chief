<?php

namespace Thinktomorrow\Chief\Forms\Dialogs\Livewire;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class TableActionDialogReference implements DialogReference
{
    private TableReference $tableReference;
    private string $actionKey;
    private string $dialogKey;

    /**
     * Unique Modal reference.
     *
     * This is used to retrieve a specific action
     * modal present in Livewire Table component.
     *
     */
    public function __construct(TableReference $tableReference, string $actionKey, string $dialogKey)
    {
        $this->tableReference = $tableReference;
        $this->actionKey = $actionKey;
        $this->dialogKey = $dialogKey;
    }

    public function toLivewire()
    {
        return [
            'class' => static::class,
            'tableReference' => $this->tableReference->toLivewire(),
            'actionKey' => $this->actionKey,
            'dialogKey' => $this->dialogKey,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            TableReference::fromLivewire($value['tableReference']),
            $value['actionKey'],
            $value['dialogKey']
        );
    }

    public function getDialog(array $parameters = []): Dialog
    {
        return $this->tableReference->getTable()->findActionDialog($this->actionKey, $this->dialogKey, $parameters);
    }
}
