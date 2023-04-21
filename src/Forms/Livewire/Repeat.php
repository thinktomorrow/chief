<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

class Repeat extends Component
{
    public array $rows = [];

    // Mount: start with one blank row...

    // nested repeats ...
    // entries are for simple repeats: key-value pairs, textual, ...

    public function render()
    {
        return view('chief-form::livewire.repeat');
    }

    public function updatedPhotos()
    {
//        $this->validate([
//            'photos.*' => 'min:5024',
//        ]);
    }

    public function save()
    {
//        $this->validate([
//            'photos.*' => 'image',
//        ]);

        foreach($this->photos as $photo) {
            $photo->store('photos');
        }

        $this->reset();
        $this->message = 'goed opgeladen';
//        dd($this->message);
//        // storing message
//        file_put_contents(__DIR__.'/test', $this->message);
    }
}
