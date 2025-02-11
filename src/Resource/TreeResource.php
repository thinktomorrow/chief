<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Collection;

interface TreeResource
{
    /**
     * For performance reasons we split up the tree model retrieval into two parts:
     * 1. getTreeModelIds() to retrieve the ids of the tree models
     * 2. getTreeModels() to retrieve the actual models by these ids
     */
    public function getTreeModelIds(): array;

    /**
     * Retrieve the actual models either all or by given ids
     */
    public function getTreeModels(?array $ids = null): Collection;
}
