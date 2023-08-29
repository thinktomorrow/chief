<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

trait EmitsToNestables
{
    private function emitDownTo($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->id, $params);
    }

    private function emitToSibling($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->parentId, $params);
    }
}
