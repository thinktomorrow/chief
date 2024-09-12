<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Collection;

interface TreeResource
{
    /**
     * For performance reasons we split up the tree model retrieval into two parts:
     * 1. getTreeModelIds() to retrieve the ids of the tree models
     * 2. getTreeModelsByIds() to retrieve the actual models by these ids
     */
    public function getTreeModelIds(): array;

    public function getTreeModelsByIds(array $ids): Collection;

//    public function getAllTreeModels(): Collection;

    // REMOVE indexRepository
    // REMOVE nestedRepository (used in SelectOptions and NestedQueries...)

    /**
     * The class responsible for fetching the results for admin index pages.
     * @return string
     */
//    public function indexRepository(): string;

//    public function getNestableNodeLabels(): ?string;


}
