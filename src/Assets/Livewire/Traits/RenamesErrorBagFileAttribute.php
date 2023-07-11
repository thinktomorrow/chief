<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Illuminate\Support\MessageBag;

trait RenamesErrorBagFileAttribute
{
    public function renameErrorBagFileAttribute()
    {
        $errorBag = new MessageBag(
            collect($this->getErrorBag())
                ->map(function ($messages, $messageKey) {
                    return collect($messages)->map(function ($message) use ($messageKey) {

                        $index = $this->extractIndexFromFileKey($messageKey);

                        if("" === $index) {
                            return $message;
                        }

                        $fileName = $this->files[$index]['fileName'];

                        // Livewire uses either the message key or returns the validation.attributes entry.
                        return str_replace([$messageKey, 'validation.attributes.' . strtolower($messageKey)], $fileName, $message);
                    });
                })
                ->toArray()
        );

        $this->setErrorBag($errorBag);
    }
}
