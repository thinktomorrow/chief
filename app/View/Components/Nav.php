<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Admin\Nav\Nav as NavItems;

final class Nav extends Component
{
    public ?string $title;

    /** @var NavItems */
    private NavItems $nav;

    public function __construct(NavItems $nav, ?string $title = null)
    {
        $this->title = $title;
        $this->nav = $nav;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::components.nav.index');
    }

    public function items(): Collection
    {
        // Attribute bag is filled at moment of method call (inside the component view)
        if ($this->attributes->has('untagged')) {
            return collect($this->nav->untagged()->all());
        }

        if ($this->attributes->has('tagged')) {
            return collect($this->nav->tagged(explode(',', $this->attributes->get('tagged')))->all());
        }

        return collect($this->nav->all());
    }
}
