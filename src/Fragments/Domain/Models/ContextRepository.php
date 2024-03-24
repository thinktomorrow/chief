<?php

namespace Thinktomorrow\Chief\Fragments\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Thinktomorrow\Chief\Locale\ChiefLocaleConfig;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class ContextRepository
{
    //    public function findOrCreateByOwner(ReferableModel $owner, string $locale): ContextModel
    //    {
    //        if($context = $this->findByOwner($owner, $locale)) {
    //            return $context;
    //        }
    //
    //        return $this->createForOwner($owner, $locale);
    //    }
    //
    // Used for nested fragments.
    public function findByFragmentOwner(ReferableModel $owner): ?ContextModel
    {
        return ContextModel::where('owner_type', $owner->modelReference()->shortClassName())
            ->where('owner_id', $owner->modelReference()->id())
            ->first();
    }

    public function getByOwner(ReferableModel $owner): \Illuminate\Support\Collection
    {
        return ContextModel::where('owner_type', $owner->modelReference()->shortClassName())
            ->where('owner_id', $owner->modelReference()->id())
            ->get();
    }

    public function getByFragment(string $fragmentId): Collection
    {
        return ContextModel::join('context_fragment_lookup', 'contexts.id', '=', 'context_fragment_lookup.context_id')
            ->where('context_fragment_lookup.fragment_id', $fragmentId)
            ->select(['contexts.*'])
            ->get();
    }

    public function find(string $contextId): ContextModel
    {
        return ContextModel::findOrFail($contextId);
    }


    //
    //    public function getOrCreateByOwner(ReferableModel $owner, array $locales): \Illuminate\Support\Collection
    //    {
    //        $locales = ChiefLocaleConfig::getLocales();
    //        $contexts = $this->getByOwner($owner);
    //
    //        if(count($contexts) < 1) {
    //            $this->createIfNotExistsForOwner($owner, $locales);
    //
    //            return $this->getByOwner($owner);
    //        }
    //
    //        return $contexts;
    //    }

    public function create(ReferableModel $owner, array $locales): ContextModel
    {
        return ContextModel::create([
            'owner_type' => $owner->modelReference()->shortClassName(),
            'owner_id' => $owner->modelReference()->id(),
            'locales' => $locales,
        ]);
    }

    // Nested fragments don't belong to a localized content. Therefor
    // we don't query by locale for nested contexts.
    // TODO: this should be changed because fragment should not have context anymore...
    public function findNestedContextByOwner(FragmentModel $owner): ?ContextModel
    {
        return ContextModel::where('owner_type', $owner::resourceKey())
            ->where('owner_id', $owner->id)
            ->first();
    }

    //    private function createIfNotExistsForOwner(ReferableModel $owner, Collection $contexts, array $locales): void
    //    {
    //        foreach($locales as $locale) {
    //            if($contexts->contains(fn (ContextModel $context) => $context->locale == $locale)) {
    //                continue;
    //            }
    //
    //            $this->createForOwner($owner, $locale);
    //        }
    //
    //    }

}