<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;

class SidebarFragment extends Component
{
    public function render()
    {
        return view('chief::layout.sidebar.fragment');
    }
}
