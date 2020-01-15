<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Relations;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Sets\SetReference;
use Thinktomorrow\Chief\Sets\StoredSetReference;

class AvailableChildren
{
    /** @var ActsAsParent */
    private $parent;

    /** @var Collection */
    private $collection;

    final private function __construct(ActsAsParent $parent)
    {
        $this->parent = $parent;
    }

    public static function forParent(ActsAsParent $parent)
    {
        return new static($parent);
    }

    public function all(): Collection
    {
        return $this->collection();
    }

    public function onlyModules(): Collection
    {
        return $this->collection()->reject(function ($item) {
            if ($item instanceof Page || $item instanceof StoredSetReference) {
                return true;
            }
        });
    }

    public function onlyPages(): Collection
    {
        return $this->collection()->filter(function ($item) {
            if ($item instanceof Page) {
                return true;
            }
        });
    }

    public static function onlySets(): Collection
    {
        // We want a regular collection, not the database one so we inject it into a regular one.
        $stored_sets = collect(StoredSetReference::all()->keyBy('key')->all());
        $all_sets    = SetReference::all();

        return $all_sets
            ->merge($stored_sets);
    }

    private function collection()
    {
        if ($this->collection) {
            return $this->collection;
        }

        $available_children_types = config('thinktomorrow.chief.relations.children', []);
        // Preload pages and modules in 2 queries to reduce calls - For some reason the collection merge looks at the model id and
        // thus overwrites 'duplicates'. This isn't expected behaviour since we have different class types.
        $collection = collect(array_merge(Page::all()->all(), Module::all()->all()));

        // Merging the results of all the pages and all the modules, then filter by the config
        // This prevents us from having duplicates and also reduces the query load.
        $collection = $collection->filter(function ($page) use ($available_children_types) {
            return in_array(get_class($page), $available_children_types);
        });

        // Filter out our already loaded pages and modules
        $remaining_children_types = collect($available_children_types)->reject(function ($type) {
            return (new $type() instanceof Page || new $type() instanceof Module);
        });

        // only for non module / page children
        foreach ($remaining_children_types as $type) {
            // For some reason the collection merge looks at the model id and
            // thus overwrites 'duplicates'. This isn't expected behaviour since we have different types.
            $collection = collect(array_merge($collection->all(), (new $type())->all()->all()));
        }
        // Filter out our parent
        return $this->collection = $collection->reject(function ($item) {
            if ($item instanceof $this->parent) {
                return $item->id == $this->parent->id;
            }

            return false;
        })->values();
    }
}
