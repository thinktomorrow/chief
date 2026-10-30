<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\Search\GuideSearch;

/**
 * The search dialog of the docs, opened from the sidebar, the overview or with ⌘K / Ctrl+K.
 */
class GuideSearchComponent extends Component
{
    public string $query = '';

    public function render(): View
    {
        return view('chief-docs::livewire.guide-search', [
            'results' => app(GuideSearch::class)->search(app(GuideTree::class), $this->query),
            'isTooShort' => GuideSearch::isTooShort($this->query),
        ]);
    }
}
