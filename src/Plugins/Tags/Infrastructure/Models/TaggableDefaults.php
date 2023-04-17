<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;

trait TaggableDefaults
{
    public function getTags(): Collection
    {
        return $this->tags->map(fn(TagModel $model) => app(TagRead::class)::fromMappedData($model->toArray()));
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(app(TagModel::class), 'owner', 'chief_tags_pivot','owner_id', 'tag_id');
    }
}
