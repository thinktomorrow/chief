<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Relations\PresentForParent;

class CollectedPages extends Collection implements PresentForParent
{
    /**
     * Present collection of pages. All pages are considered to be of the same collection type.
     *
     * @param ActsAsParent $parent
     * @return string
     */
    public function presentForParent(ActsAsParent $parent): string
    {
        $guessedParentViewName = $parent->collectionKey();
        $guessedViewName = $this->collectionKey();
        $viewPaths = ['front.modules.'.$guessedParentViewName.'.'.$guessedViewName, 'front.modules.'.$guessedViewName];

        foreach ($viewPaths as $viewPath) {
            if (! view()->exists($viewPath)) {
                continue;
            }

            return view($viewPath, [
                'pages' => $this->all(),
                'parent' => $parent,
            ])->render();
        }

        return '';
    }

    private function collectionKey()
    {
        return $this->first()->collectionKey();
    }
}