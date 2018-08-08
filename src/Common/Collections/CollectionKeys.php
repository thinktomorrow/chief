<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;

class CollectionKeys
{
    /** @var array */
    private $pairs;

    private function __construct(array $pairs)
    {
        $this->pairs = $pairs;
    }

    public static function fromConfig()
    {
        return new static(static::configuratedPairs());
    }

    public function filterByKey(string $key): self
    {
        if (!isset($this->pairs[$key])) {
            throw new NotFoundCollectionKey('No corresponding class found for the collection key ['.$key.']. Make sure to add this to the [thinktomorrow.chief.collections] config array.');
        }

        return new static([$key => $this->pairs[$key]]);
    }

    public function rejectByKey(string $key): self
    {
        if (isset($this->pairs[$key])) {
            unset($this->pairs[$key]);
        }

        return new static($this->pairs);
    }

    public function filterByClass(string $class): self
    {
        if (false == ($key = array_search($class, $this->pairs))) {
            throw new NotFoundCollectionKey('Collection key expected but none found for ' . $class.'. Please provide a collection key in the chief config file and on your model as model::collection property.');
        }

        return new static([$key => $class]);
    }

    /**
     * Filter the collection pairs by their parent type: either 'pages' or 'modules'
     *
     * @param string $type
     * @return self
     */
    public function filterByType(string $type): self
    {
        $parentInstance = $this->parentInstanceByType($type);

        $filtered = collect($this->pairs)->filter(function ($className) use ($parentInstance) {
            return (new $className instanceof $parentInstance);
        });

        return new static($filtered->toArray());
    }

    private function parentInstanceByType($type)
    {
        if ($type == 'pages') {
            return Page::class;
        }

        if ($type == 'modules') {
            return Module::class;
        }

        throw new \DomainException('Invalid collection type [' . $type .'], should be either pages or modules');
    }

    public function toKeys(): array
    {
        return array_keys($this->pairs);
    }

    /**
     * Return the key of the first entry
     * @return string
     */
    public function toKey(): string
    {
        return array_first($this->toKeys());
    }

    public function toCollectionDetails(): Collection
    {
        return collect($this->pairs)->map(function ($className) {
            return (new $className)->collectionDetails();
        });
    }

    public function toCollectionDetail(): CollectionDetails
    {
        return $this->toCollectionDetails()->first();
    }

    /**
     * List of available collection keys and their corresponding models
     * @return array
     */
    private static function configuratedPairs(): array
    {
        return config('thinktomorrow.chief.collections', []);
    }
}
