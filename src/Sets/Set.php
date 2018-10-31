<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Sets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Morphable\MorphableContract;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Relations\PresentForParent;
use Thinktomorrow\Chief\Snippets\WithSnippets;

class Set extends Collection implements PresentForParent
{
    use WithSnippets;

    /** @var string */
    private $viewKey;

    public function __construct($items = [], string $viewKey)
    {
        $this->viewKey = $viewKey;

        $this->constructWithSnippets();

        parent::__construct($items);
    }

    public function viewKey(): string
    {
        return $this->viewKey;
    }

    public static function fromReference(SetReference $setReference): Set
    {
        return $setReference->toSet();
    }

    public function presentForParent(ActsAsParent $parent): string
    {
        $value = $this->presentRawValueForParent($parent);

        if ($this->withSnippets && $this->shouldParseWithSnippets($value)) {
            $value = $this->parseWithSnippets($value);
        }

        return $value;
    }

    /**
     * Present collection of pages. All pages are considered to be of the same collection type.
     *
     * @param ActsAsParent $parent
     * @return string
     * @throws \Throwable
     */
    private function presentRawValueForParent(ActsAsParent $parent): string
    {
        $viewPaths = [
            'front.modules.'. $parent->viewKey().'.'.$this->viewKey(),
            'front.modules.'.$this->viewKey(),
        ];

        // In case the collection is made out of pages, we'll also allow to use the
        // generic collection page view for these sets as well.
        if($this->first() instanceof PresentForParent){
            $viewPaths[] = 'front.modules.'. $parent->viewKey().'.'.$this->first()->viewKey();
            $viewPaths[] = 'front.modules.'. $this->first()->viewKey();
        }

        foreach ($viewPaths as $viewPath) {
            if (! view()->exists($viewPath)) {
                continue;
            }

            return view($viewPath, [
                'collection'  => $this,
                'parent'     => $parent,

                /** @deprecated Backward compatibility for current modules where pages is passed  */
                'pages'  => $this,
            ])->render();
        }

        // If no view has been created for this page collection, we try once again to fetch the content value if any. This will silently fail
        // if no content value is present. We don't consider the 'content' attribute to be a default as we do for module.
        return $this->map(function($item) use($parent){

            return ($item instanceof PresentForParent)
                ? $item->presentForParent($parent)
                : ($item->content ?? '');

        })->implode('');
    }

    /**
     * Override the collection map function to include the key
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items), $this->viewKey);
    }
}