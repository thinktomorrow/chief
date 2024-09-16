<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

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
