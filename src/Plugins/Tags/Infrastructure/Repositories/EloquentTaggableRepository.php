<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;

class EloquentTaggableRepository implements \Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository
{
    public function attachTags(string $ownerType, array $taggableIds, array $tagIds): void
    {
        // Create combination of taggable and tag ids
        $matrix = collect($taggableIds)->crossJoin($tagIds);

        // Create insert query
        $insertQuery = [];

        foreach ($matrix->all() as $pair) {
            $insertQuery[] = ['owner_type' => $ownerType, 'owner_id' => $pair[0], 'tag_id' => $pair[1]];
        }

        DB::table('chief_tags_pivot')->insertOrIgnore($insertQuery);
    }

    public function detachTags(string $ownerType, array $taggableIds, array $tagIds): void
    {
        // Create combination of taggable and tag ids
        $matrix = collect($taggableIds)->crossJoin($tagIds);

        foreach ($matrix->all() as $pair) {
            DB::table('chief_tags_pivot')
                ->where('owner_type', $ownerType)
                ->where('owner_id', $pair[0])
                ->where('tag_id', $pair[1])
                ->delete();
        }
    }

    public function syncTags(string $ownerType, array $taggableIds, array $tagIds): void
    {
        DB::table('chief_tags_pivot')
            ->where('owner_type', $ownerType)
            ->whereIn('owner_id', $taggableIds)
            ->whereNotIn('tag_id', $tagIds)
            ->delete();

        $this->attachTags($ownerType, $taggableIds, $tagIds);
    }
}
