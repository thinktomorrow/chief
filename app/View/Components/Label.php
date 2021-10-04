<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;

class Label extends Component
{
    public function __construct($size = 'sm', $type = 'info')
    {
        $this->size = $size;
        $this->type = $type;
    }

    public function render()
    {
        return view('chief::components.label', [
            'sizeStyle' => $this->getSizeStyle(),
            'typeStyle' => $this->getTypeStyle()
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
            default:
                return 'label-sm';
        }
    }

    public function getTypeStyle(): string
    {
        switch($this->type ?? null) {
            case 'error':
                return 'label-error';
            case 'success':
                return 'label-success';
            case 'info':
                return 'label-info';
            case 'warning':
                return 'label-warning';
            default:
                return 'label-info';
        }
    }
}
