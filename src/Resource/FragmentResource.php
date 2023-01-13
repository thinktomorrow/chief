<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;

interface FragmentResource extends Resource
{
    public function adminView(): View;

    /**
     * The svg representation of this fragment. This is
     * used in the fragment selection screens.
     */
    public function getIcon(): string;

    /**
     * Short description of the fragment to hint
     * the user when selecting a new fragment.
     */
    public function getHint(): ?string;

    /**
     * Categorize this fragment. Especially helpful to better
     * present the available fragments to the webmaster.
     */
    public function getCategory(): ?string;
}
