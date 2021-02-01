<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Admin\Nav\Nav as NavItems;

final class Nav extends Component
{
    public string $title;

    /** @var NavItems */
    private NavItems $nav;

    public function __construct(NavItems $nav, string $title = 'models')
    {
        $this->title = $title;
        $this->nav = $nav;
    }

    public function render()
    {
        return view('chief::components.nav');
    }

    public function items(): Collection
    {
        // Attribute bag is filled at moment of method call (inside the component view)
        if($this->attributes->has('untagged')){
            return collect($this->nav->untagged()->all());
        }

        if($this->attributes->has('tagged')) {
            return collect($this->nav->tagged(explode(',', $this->attributes->get('tagged')))->all());
        }

        return collect($this->nav->all());
    }
}
