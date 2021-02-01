<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Morphable;

class ArticleModel extends MorphableModel
{
    public function children()
    {
        return $this->hasMany(ArticleModel::class, 'parent_id');
    }
}
