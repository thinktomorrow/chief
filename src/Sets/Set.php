<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Sets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Snippets\WithSnippets;
use Illuminate\Contracts\Pagination\Paginator;
use Thinktomorrow\Chief\Concerns\Viewable\ViewableContract;

class Set extends Collection implements ViewableContract
{
    use WithSnippets,
        Viewable{
            renderView as baseRenderView;
        }

    protected $baseViewPath;
    protected $viewKey;

    public function __construct($items = [], string $viewKey)
    {
        $this->viewKey = $viewKey;

        if (!isset($this->baseViewPath)) {
            $this->baseViewPath = config('thinktomorrow.chief.base-view-paths.sets', 'sets');
        }

        $this->constructWithSnippets();

        parent::__construct($items);
    }

    public static function fromReference(SetReference $setReference): Set
    {
        return $setReference->toSet();
    }

    public function renderView(): string
    {
        if ($result = $this->baseRenderView()) {
            return $result;
        }

        // If no view has been created for this page collection, we try once again to fetch the content value if any. This will silently fail
        // if no content value is present. We don't consider the 'content' attribute to be a default as we do for module.
        return $this->map(function ($item) {
            return ($this->viewParent && $item instanceof ViewableContract)
                ? $item->setViewParent($this->viewParent)->renderView()
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

    /**
     * Paginate the collection with a simple navigation (prev and next)
     *
     * @param int $perPage
     * @param null $currentPage
     * @return Paginator
     */
    public function simplePaginate($perPage = 12, $currentPage = null): Paginator
    {
        $currentPage = $currentPage ?? request()->get('page', 1);
        $path = request()->path();
        $items = array_slice($this->all(), ($currentPage - 1) * $perPage);

        return (new \Illuminate\Pagination\Paginator($items, $perPage, $currentPage))->setPath($path);
    }

    /**
     * Paginate the collection with a length aware pagination result which allows
     * to navigate to the first, last or any specific page
     *
     * @param int $perPage
     * @param null $currentPage
     * @return Paginator
     */
    public function paginate($perPage = 12, $currentPage = null): Paginator
    {
        $currentPage = $currentPage ?? request()->get('page', 1);
        $path = request()->path();
        $items = array_slice($this->all(), ($currentPage - 1) * $perPage, $perPage);

        return (new \Illuminate\Pagination\LengthAwarePaginator($items, $this->count(), $perPage, $currentPage))->setPath($path);
    }
}
