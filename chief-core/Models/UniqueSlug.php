<?php

namespace Chief\Models;

use Illuminate\Support\Str;

class UniqueSlug
{
    /**
     * @var SluggableContract
     */
    private $model;

    public function __construct(SluggableContract $model)
    {
        $this->model = $model;
    }

    public static function make($model)
    {
        return new static($model);
    }

    public function get($title,SluggableContract $entity = null)
    {
        $slug = $originalslug = Str::slug($title);
        $i = 1;

        while(!$this->isSlugUnique($slug,$entity))
        {
            $slug = $originalslug.'-'.$i;
            $i++;
        }

        return $slug;
    }

    /**
     *
     * @param $slug
     * @param SluggableContract $entity
     * @return bool
     */
    private function isSlugUnique($slug,SluggableContract $entity = null)
    {
        $model = $this->model->findBySlug($slug);

        if(!$model || ($entity && $entity->id && $model->id == $entity->id)) return true;

        return false;
    }
}