<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;

class Index extends Component
{
    public bool $sidebar = true;

    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct(bool $sidebar = true)
    {
        $this->sidebar = $sidebar;
    }

    public function render()
    {
        return view('chief::layout.index');
    }
}
