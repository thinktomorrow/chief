<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;

class Fragment extends Component
{
    public function render()
    {
        return view('chief::layout.fragment');
    }
}
