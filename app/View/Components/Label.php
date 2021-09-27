<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;

class Label extends Component
{
    public function __construct($size = 'md')
    {
        /* FIXME: Attribute not being passed to model */
        $this->size = $size;
    }

    public function render()
    {
        return view('chief::components.label', [
            'sizeStyle' => $this->getSizeStyle()
        ]);
    }

    public function getSizeStyle(): string
    {
        switch($this->size ?? null) {
            case 'xs':
                return 'label-xs';
            case 'sm':
                return 'label-sm';
            case 'md':
                return 'label-md';
            case 'lg':
                return 'label-lg';
            case 'xl':
                return 'label-xl';
            default:
                return 'label-md';
        }
    }
}
