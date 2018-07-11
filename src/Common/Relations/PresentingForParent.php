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

        return '';
    }
}