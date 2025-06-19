<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

trait InteractsWithBasename
{
    private function addDefaultBasenameValidation(array $rules = [], array $messages = [], array $validationAttributes = []): array
    {
        $rules = array_merge($rules, ['form.basename' => ['required', 'min:1', 'max:200']]);
        $messages = array_merge($messages, []);
        $validationAttributes = array_merge($validationAttributes, ['form.basename' => 'bestandsnaam']);

        return [$rules, $messages, $validationAttributes];
    }
}
