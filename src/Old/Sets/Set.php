<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Sets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Snippets\WithSnippets;
use Illuminate\Contracts\Pagination\Paginator;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\PageBuilder\Relations\ActsAsParent;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;

class Set extends Collection implements ViewableContract
{
    use WithSnippets,
        Viewable {
        renderView as baseRenderView;
    }

    protected $baseViewPath;
    protected $viewKey;

    /** @var array */
    private $settings;

    final public function __construct(array $items, string $viewKey, array $settings = [])
    {
        $this->viewKey = $viewKey;

        if (!isset($this->baseViewPath)) {
            $this->baseViewPath = config('chief.base-view-paths.sets', 'sets');
        }

        $this->constructWithSnippets();

        parent::__construct($items);
        $this->settings = $settings;
    }

    public static function fromReference(SetReference $setReference, ActsAsParent $parent): Set
    {
        return $setReference->toSet($parent);
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
     * @param callable $callback
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
    public function simplePaginate($perPage = null, $currentPage = null): Paginator
    {
        $currentPage = $currentPage ?? request()->get('page', 1);
        $path = request()->path();

        // When we have an entire collection as result we will cut down the items here, else if already paginated, we'll keep it as is.
        $items = $this->comingFromPaginatedCollection() ? $this->all() :array_slice($this->all(), ($currentPage - 1) * $perPage);

        return (new \Illuminate\Pagination\Paginator($items, $perPage ?? $this->paginateSetting('perPage', '12'), $currentPage))->setPath($path);
    }

    /**
     * Paginate the collection with a length aware pagination result which allows
     * to navigate to the first, last or any specific page
     *
     * @param int $perPage
     * @param null $currentPage
     * @return Paginator
     */
    public function paginate($perPage = null, $currentPage = null): Paginator
    {
        $currentPage = $currentPage ?? request()->get('page', 1);
        $path = '/' . request()->path();

        // When we have an entire collection as result we will cut down the items here, else if already paginated, we'll keep it as is.
        $items = $this->comingFromPaginatedCollection() ? $this->all() : array_slice($this->all(), ($currentPage - 1) * $perPage, $perPage);

        return (new \Illuminate\Pagination\LengthAwarePaginator($items, $this->paginateSetting('total', $this->count()), $perPage ?? $this->paginateSetting('perPage', '12'), $currentPage))->setPath($path);
    }

    private function comingFromPaginatedCollection(): bool
    {
        return isset($this->settings['paginate']);
    }

    private function paginateSetting($key, $default = null)
    {
        if (!isset($this->settings['paginate']) || !isset($this->settings['paginate'][$key])) {
            return $default;
        }

        return $this->settings['paginate'][$key];
    }
}
