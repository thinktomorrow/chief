<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Nav;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Register;

class Nav
{
    /** @var array */
    private $items;

    final private function __construct(NavItem ...$items)
    {
        $this->items = $items;
    }

    public static function fromKeys($keys)
    {
        $keys = (array)$keys;
        $collection = collect();

        /** @var Managers */
        $managers = app(Managers::class);
        foreach ($keys as $key) {
            $collection->push($managers->findByKey($key));
        }

        return static::fromManagers($collection);
    }

    public static function fromTags($tags)
    {
        $tags = (array)$tags;
        $collection = collect();

        /** @var Managers */
        $managers = app(Managers::class);
        foreach ($tags as $tag) {
            $collection = $collection->merge($managers->findByTag($tag));
        }

        return static::fromManagers($collection);
    }

    public static function allManagers()
    {
        return static::fromManagers(app(Managers::class)->all());
    }

    private static function fromManagers(Collection $collection)
    {
        return new static(...$collection->reject(function ($manager) {
            return !$manager->can('index');
        })->map(function ($manager) {
            return new NavItem($manager->details()->plural, $manager->route('index'), [
                'key'  => $manager->details()->key,
                'tags' => app(Register::class)->filterByKey($manager->details()->key)->first()->tags(),
            ]);
        })->values()->toArray());
    }

    public function rejectKeys($keys)
    {
        $keys = (array)$keys;

        foreach ($this->items as $k => $item) {
            if (in_array($item->details('key', ''), $keys)) {
                unset($this->items[$k]);
            }
        }

        return $this;
    }

    public function rejectTags($tags)
    {
        $tags = (array)$tags;

        foreach ($this->items as $k => $item) {
            if (count(array_intersect($item->details('tags', []), $tags)) > 0) {
                unset($this->items[$k]);
            }
        }

        return $this;
    }

    public function add(string $title, string $url)
    {
        $this->items[] = new NavItem($title, $url);

        return $this;
    }

    public function addManager(Manager $manager)
    {
        if (!$manager->can('index')) {
            return $this;
        }

        $this->items[] = new NavItem($manager->details()->plural, $manager->route('index'), [
            'key' => $manager->details()->key,
        ]);

        return $this;
    }

    /**
     * Render a single nav link at top level for each item
     *
     * @param null $title
     * @return string
     */
    public function render($title = null): string
    {
        $output = '';

        foreach ($this->items as $item) {
            $output .= '<a class="' . (isActiveUrl($item->url()) ? 'active' : '') . '" href="' . $item->url() . '">';
            $output .= $title ?? ucfirst($item->title());
            $output .= '</a>';
        }

        return '<li>' . $output . '</li>';
    }

    /**
     * Render items to be placed in the chief admin nav dropdown
     *
     * @param null $title
     * @return string
     */
    public function renderItems($title = null): string
    {
        if (empty($this->items)) {
            return '';
        }

        // Don't bother using a dropdown if there's only one item.
        if (count($this->items) == 1) {
            return $this->render();
        }

        $items = '';
        foreach ($this->items as $item) {
            $items .= '<a class="' . (isActiveUrl($item->url()) ? 'active' : '') . '" href="' . $item->url() . '">';
            $items .= ucfirst($item->title());
            $items .= '</a>';
        }

        // Surround within vue dropdown
        $output = '<dropdown>';
        $output .= '<span class="center-y nav-item" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">' . ($title ?? 'Collecties') . '</span>';
        $output .= '<div v-cloak class="dropdown-box inset-s">';
        $output .= $items;
        $output .= '</div>';
        $output .= '</dropdown>';

        return '<li>' . $output . '</li>';
    }
}
