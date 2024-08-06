<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;

class EloquentTaggableRepository implements \Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository
{
    public function attachTags(array $taggableIds, array $tagIds): void
    {
        // Create array for a combination of taggable and tag ids
        $matrix = collect($taggableIds)->crossJoin($tagIds);
        dd($taggableIds, $tagIds, $matrix->all());


        // insertIfNotExists($matrix, function ($taggableId, $tagId) {

// INSERT INTO table_name (column1, column2, ...) SELECT value1, value2, ... WHERE NOT EXISTS ( SELECT 1 FROM table_name WHERE condition );

        //place with your actual model // Define the data you want to insert $data = [ 'username' => 'value1', 'passaword' => 'value2' ]; // Write the raw SQL query $query = "INSERT INTO users (username, passaword) SELECT :username WHERE NOT EXISTS ( SELECT 1 FROM users WHERE username = :column1 )"; // Execute the raw SQL query DB::statement($query, $dataToInsert);

        // Attach tag to pivot if it doesn't exist yet
        foreach ($taggableIds as $taggableId) {
            foreach ($tagIds as $tagId) {
                $taggable = Taggable::find($taggableId);
                $taggable->tags()->attach($tagId);
            }
        }
    }

    public function detachTags(array $taggableIds, array $tagIds): void
    {
        // TODO: Implement detachTags() method.
    }

    public function syncTags(Taggable $taggable, array $tagIds): void
    {
        $taggable->tags()->sync($tagIds);
    }
}
