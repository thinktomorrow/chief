<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * @var bool
     */
    public bool $sidebar = true;

    /**
     * Create the component instance.
     *
     * @param bool $sidebar
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
