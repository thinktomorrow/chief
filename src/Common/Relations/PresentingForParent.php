<?php


namespace Thinktomorrow\Chief\Common\Relations;

trait PresentingForParent
{
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
                'module' => $this,
                'parent' => $parent,
            ])->render();
        }

        // If no view has been created for this module, we try once again to fetch the content value if any. This will silently fail
        // if no content value is present. We consider the 'content' attribute to be a default for module and page copy.
        return $this->content ?? '';
    }
}
