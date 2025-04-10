<?php

namespace Thinktomorrow\Chief\Fragments\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ContextRepository
{
    public function findBySite(ModelReference $modelReference, string $site): ?ContextModel
    {
        return ContextModel::byActiveSite($site)
            ->where('owner_type', $modelReference->shortClassName())
            ->where('owner_id', $modelReference->id())
            ->first();
    }

    public function getByOwner(ModelReference $modelReference): \Illuminate\Support\Collection
    {
        return ContextModel::where('owner_type', $modelReference->shortClassName())
            ->with('owner')
            ->where('owner_id', $modelReference->id())
            ->get();
    }

    public function guessContextIdForSite(ModelReference $modelReference, string $site): ?string
    {
        // So filled active_sites are first before the empty ones
        $contextId = ContextModel::byActiveSiteOrNone($site)
            ->where('owner_type', $modelReference->shortClassName())
            ->where('owner_id', $modelReference->id())
            ->select('id')
            ->first()?->id;

        return $contextId ? (string) $contextId : null;
    }

    public function find(string $contextId): ContextModel
    {
        return ContextModel::with('owner')->findOrFail($contextId);
    }

    public function create(ModelReference $ownerReference, array $locales, array $activeSites, ?string $title = null): ContextModel
    {
        return ContextModel::create([
            'owner_type' => $ownerReference->shortClassName(),
            'owner_id' => $ownerReference->id(),
            'locales' => $locales,
            'active_sites' => $activeSites,
            'title' => $title,
        ]);
    }

    public function getContextsByFragment(string $fragmentId): Collection
    {
        return ContextModel::join('context_fragment_tree', 'contexts.id', '=', 'context_fragment_tree.context_id')
            ->where('context_fragment_tree.child_id', $fragmentId)
            ->select(['contexts.*'])
            ->with('owner')
            ->get();
    }

    public function countContexts(ModelReference $modelReference): int
    {
        return ContextModel::where('owner_type', $modelReference->shortClassName())
            ->where('owner_id', $modelReference->id())
            ->count();
    }

    public function countFragments(string $fragmentId): int
    {
        return DB::table('context_fragment_tree')
            ->where('child_id', $fragmentId)
            ->count();
    }
}
