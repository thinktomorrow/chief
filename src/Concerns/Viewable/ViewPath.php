<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Concerns\Viewable;

use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Sets\Set;

class ViewPath
{
    /** @var ViewableContract */
    private $viewable;

    /** @var ActsAsParent */
    private $parent;

    /** @var string */
    private $viewBasePath;

    final private function __construct(ViewableContract $viewable, ActsAsParent $parent = null, string $viewBasePath = null)
    {
        $this->viewable = $viewable;
        $this->parent = $parent;
        $this->viewBasePath = $viewBasePath;
    }

    public static function make(ViewableContract $viewable, ActsAsParent $parent = null, string $viewBasePath = null)
    {
        return new static($viewable, $parent, $viewBasePath);
    }

    /**
     * @return string
     * @throws NotFoundView
     */
    public function get(): string
    {
        $basePath = $this->viewBasePath ? $this->viewBasePath . '.' : '';
        $guessedParentViewName = $this->parent ? $this->parent->viewKey() : '';
        $guessedViewName = $this->viewable->viewKey();

        $viewPaths = [
            $basePath.$guessedParentViewName.'.'.$guessedViewName,
            $basePath.$guessedViewName,
            $basePath.$guessedViewName.'.show',
            $basePath.'show',
        ];

        // In case the collection set is made out of pages, we'll also allow to use the
        // generic collection page view for these sets as well as a fallback view
        if ($this->viewable instanceof Set && $this->viewable->first() instanceof ViewableContract) {
            $viewPaths[] = $basePath.$guessedParentViewName.'.'.$this->viewable->first()->viewKey();
            $viewPaths[] = $basePath.$this->viewable->first()->viewKey();
        }

        foreach ($viewPaths as $viewPath) {
            if (! view()->exists($viewPath)) {
                continue;
            }

            return $viewPath;
        }

        if (! view()->exists($basePath.'show')) {
            throw new NotFoundView('Viewfile not found for ['.get_class($this->viewable).']. Make sure the view ['.$basePath.$guessedViewName.'] or the default view ['.$basePath.'show] exists.');
        }
    }
}
