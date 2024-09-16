<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Forms\Dialogs\Livewire\TableActionDialogReference;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Actions\BulkAction;

trait WithNotifications
{
    public function showNotification($notification, $type = 'success')
    {
        $this->dispatch('create-notification', [
            'type' => $type,
            'content' => $notification,
            'duration' => 2000,
        ]);
    }
}
