<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

trait EmitsToNestables
{
    private function emitDownTo($name, $event, array $params = [])
    {
        $this->dispatch($event.'-'.$this->getId(), $params)->to($name);
    }

    private function emitToSibling($name, $event, array $params = [])
    {
        $params = array_merge($params, ['previous_sibling_id' => $this->getId()]);

        $this->dispatch($event.'-'.$this->parentId, $params)->to($name);
    }
}
