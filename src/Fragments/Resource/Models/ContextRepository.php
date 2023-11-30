<?php

namespace Thinktomorrow\Chief\Fragments\Resource\Models;

use Illuminate\Database\Eloquent\Collection;
use Thinktomorrow\Chief\Locale\ChiefLocaleConfig;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class ContextRepository
{
    public function findOrCreateByOwner(ReferableModel $owner, string $locale): ContextModel
    {
        if($context = $this->findByOwner($owner, $locale)) {
            return $context;
        }

        return $this->createForOwner($owner, $locale);
    }

    public function findByOwner(ReferableModel $owner, string $locale): ?ContextModel
    {
        return ContextModel::where('owner_type', $owner->modelReference()->shortClassName())
            ->where('owner_id', $owner->modelReference()->id())
            ->where('locale', $locale)
            ->first();
    }

    public function createForOwner(ReferableModel $owner, string $locale): ContextModel
    {
        return ContextModel::create([
            'owner_type' => $owner->modelReference()->shortClassName(),
            'owner_id' => $owner->modelReference()->id(),
            'locale' => $locale,
        ]);
    }

    public function getByOwner(ReferableModel $owner): \Illuminate\Support\Collection
    {
        return ContextModel::where('owner_type', $owner->modelReference()->shortClassName())
            ->where('owner_id', $owner->modelReference()->id())
            ->get();
    }

    public function getOrCreateByOwner(ReferableModel $owner): \Illuminate\Support\Collection
    {
        $locales = ChiefLocaleConfig::getLocales();
        $contexts = $this->getByOwner($owner);

        if(count($contexts) < count($locales)) {
            $this->createIfNotExistsForOwner($owner, $locales);
            return $this->getByOwner($owner);
        }

        return $contexts;
    }

    public function getByFragment(string $fragmentId): Collection
    {
        return ContextModel::join('context_fragment_lookup', 'contexts.id', '=', 'context_fragment_lookup.context_id')
            ->where('context_fragment_lookup.fragment_id', $fragmentId)
            ->select(['contexts.*'])
            ->get();
    }

    // Nested fragments don't belong to a localized content. Therefor
    // we don't query by locale for nested contexts.
    public function findNestedContextByOwner(FragmentModel $owner): ?ContextModel
    {
        return ContextModel::where('owner_type', $owner::resourceKey())
            ->where('owner_id', $owner->id)
            ->first();
    }

    private function createIfNotExistsForOwner(ReferableModel $owner, Collection $contexts, array $locales): void
    {
        foreach($locales as $locale) {
            if($contexts->contains(fn(ContextModel $context) => $context->locale == $locale)) continue;

            $this->createForOwner($owner, $locale);
        }

    }

}
