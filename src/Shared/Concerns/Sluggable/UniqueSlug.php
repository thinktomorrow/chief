<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Sluggable;

use Illuminate\Support\Str;

class UniqueSlug
{
    /** @var SluggableContract */
    private $model;

    /** @var array */
    private $blacklist;

    /** @var \Closure */
    private $slugResolver;

    final public function __construct(SluggableContract $model, array $blacklist = [])
    {
        $this->model = $model;
        $this->blacklist = $blacklist;

        $this->slugResolver = function ($slug): string {
            return Str::slug($slug);
        };
    }

    /**
     * @return static
     */
    public static function make($model, array $blacklist = []): self
    {
        return new static($model, $blacklist);
    }

    /**
     * @return static
     */
    public function slugResolver(\Closure $resolver): self
    {
        $this->slugResolver = $resolver;

        return $this;
    }

    public function get($title, ?SluggableContract $entity = null)
    {
        $slug = $originalslug = $this->sluggify($title);
        $i = 1;

        while (! $this->isSlugUnique($slug, $entity)) {
            $slug = $originalslug.'-'.$i;
            $i++;
        }

        // Add to blacklist
        $this->blacklist[] = $slug;

        return $slug;
    }

    private function sluggify($value)
    {
        return call_user_func($this->slugResolver, $value);
    }

    /**
     * @return bool
     */
    private function isSlugUnique($slug, ?SluggableContract $entity = null)
    {
        $model = $this->model->findBySlug($slug);

        if (! in_array($slug, $this->blacklist) && (! $model || ($entity && $entity->id && $model->id == $entity->id))) {
            return true;
        }

        return false;
    }
}
