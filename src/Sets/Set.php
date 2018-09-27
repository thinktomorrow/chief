<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Sets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Relations\PresentForParent;
use Thinktomorrow\Chief\Snippets\WithSnippets;

class Set extends Collection implements PresentForParent
{
    use WithSnippets;

    /** @var string */
    private $key;

    public function __construct($items = [], string $key)
    {
        $this->key = $key;

        $this->constructWithSnippets();

        parent::__construct($items);
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
        $guessedParentViewName = $parent->collectionKey();
        $guessedSetViewName = $this->key;

        $viewPaths = [
            'front.modules.'.$guessedParentViewName.'.'.$guessedSetViewName,
            'front.modules.'.$guessedSetViewName,
        ];

        foreach ($viewPaths as $viewPath) {
            if (! view()->exists($viewPath)) {
                continue;
            }

            return view($viewPath, [
                'collection'  => $this->all(),

                /** @deprecated Backward compatibility for current modules where pages is passed  */
                'pages'  => $this->all(),
                'parent'     => $parent,
            ])->render();
        }

        // If no view has been created for this page collection, we try once again to fetch the content value if any. This will silently fail
        // if no content value is present. We don't consider the 'content' attribute to be a default as we do for module.
        return '';
    }
}