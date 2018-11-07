<?php

namespace Thinktomorrow\Chief\Concerns\Sluggable;

use Thinktomorrow\Chief\Concerns\Sluggable\SluggableContract;

class UniqueSlug
{
    /** @var SluggableContract */
    private $model;

    /** @var array */
    private $blacklist;

    /** @var \Closure */
    private $slugResolver;

    public function __construct(SluggableContract $model, array $blacklist = [])
    {
        $this->model = $model;
        $this->blacklist = $blacklist;

        $this->slugResolver = function ($slug) {
            return str_slug($slug);
        };
    }

    public static function make($model, array $blacklist = [])
    {
        return new static($model, $blacklist);
    }

    public function slugResolver(\Closure $resolver)
    {
        $this->slugResolver = $resolver;

        return $this;
    }

    public function get($title, SluggableContract $entity = null)
    {
        $slug = $originalslug = $this->sluggify($title);
        $i = 1;

        while (!$this->isSlugUnique($slug, $entity)) {
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
     *
     * @param $slug
     * @param SluggableContract $entity
     * @return bool
     */
    private function isSlugUnique($slug, SluggableContract $entity = null)
    {
        $model = $this->model->findBySlug($slug);

        if (!in_array($slug, $this->blacklist) && (!$model || ($entity && $entity->id && $model->id == $entity->id))) {
            return true;
        }

        return false;
    }
}
