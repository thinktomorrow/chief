<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Register;

final class TaggedKeys
{
    private array $map;

    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    public function tag(string $key, array $tags): self
    {
        $this->map = array_merge($this->map, [$key => $tags]);

        return $this;
    }

    public function tagged($tags): self
    {
        $tags = (array) $tags;

        return $this->filter(function($value, $key) use($tags){
            return count(array_intersect($value, $tags)) > 0;
        });
    }

    public function notTagged($tags): self
    {
        $tags = (array) $tags;

        return $this->filter(function($value, $key) use($tags){
            return count(array_intersect($value, $tags)) === 0;
        });
    }

    public function untagged(): self
    {
        return $this->filter(function($value){
            return empty($value);
        });
    }

    public function getKeys(): array
    {
        return array_keys($this->map);
    }

    public function get(): array

    {
        return $this->map;
    }

    private function filter(\Closure $closure): self
    {
        $filtered = array_filter($this->map, $closure, ARRAY_FILTER_USE_BOTH);

        return new static($filtered);
    }
}
