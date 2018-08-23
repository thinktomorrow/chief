<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\PageSets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Relations\PresentForParent;
use Thinktomorrow\Chief\Pages\Page;

class PageSet extends Collection implements PresentForParent
{
    public function __construct($items = [])
    {
        $this->validateItems($items);

        parent::__construct($items);
    }

    public static function fromReference(PageSetReference $pageSetReference): PageSet
    {
        return $pageSetReference->toPageSet();
    }

    /**
     * Present collection of pages. All pages are considered to be of the same collection type.
     *
     * @param ActsAsParent $parent
     * @return string
     * @throws \Throwable
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

        // If no view has been created for this page collection, we try once again to fetch the content value if any. This will silently fail
        // if no content value is present. We don't consider the 'content' attribute to be a default as we do for module.
        return '';
    }

    private function collectionKey()
    {
        return $this->first()->collectionKey();
    }

    /**
     * @param $items
     */
    private function validateItems($items): void
    {
        foreach($items as $item){
            if(! $item instanceof Page) {
                throw new \InvalidArgumentException('PageSet collection accepts only Page objects: ' . $e->getMessage());
            }
        }
    }
}