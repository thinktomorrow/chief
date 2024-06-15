<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Repositories;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Thinktomorrow\Chief\Fragments\App\ActiveContext\FragmentCollection;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

final class FragmentRepository
{
    private FragmentFactory $fragmentFactory;

    public function __construct(FragmentFactory $fragmentFactory)
    {
        $this->fragmentFactory = $fragmentFactory;
    }

    /**
     * Get entire fragmentCollection for a given context.
     * This is used to render all page fragments.
     */
    public function getTreeByContext(string $contextId, ?string $locale = null): FragmentCollection
    {
        $fragmentModels = $this->getByContext($contextId, $locale);

        return FragmentCollection::fromIterable($fragmentModels, function (Fragment $fragment) {
            $fragment->id = $fragment->fragmentModel()->id;
            $fragment->parent_id = $fragment->fragmentModel()->pivot->parent_id;
            $fragment->order = $fragment->fragmentModel()->pivot->order;

            return $fragment;
        })->sort('order');
    }

    public function getByContext(string $contextId, ?string $locale = null): Collection
    {
        $fragmentModels = ContextModel::findOrFail($contextId)
            ->fragments()
            ->when($locale, fn ($query, $locale) => $query->where(function ($q) use ($locale) {
                $q->whereJsonContains('context_fragment_tree.locales', $locale)
                  ->orWhereNull('context_fragment_tree.locales')
                  ->orWhereJsonLength('context_fragment_tree.locales', '=', 0);
            }))
                ->with('assetRelation', 'assetRelation.media')
                ->get();

        return $fragmentModels->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory->create($fragmentModel));
    }

    /**
     * Find a fragment for a specific context.
     * This includes the context pivot data
     */
    public function findByContext(string $fragmentId, string $contextId): Fragment
    {
        $fragmentModel = ContextModel::findOrFail($contextId)->fragments()->find($fragmentId);

        return $this->fragmentFactory->create($fragmentModel);
    }

    public function exists(string $fragmentId): bool
    {
        return FragmentModel::where('id', $fragmentId)->exists();
    }

    public function find(string $fragmentId): Fragment
    {
        return $this->fragmentFactory->create(FragmentModel::findOrFail($fragmentId));
    }

    public function nextId(): string
    {
        $nextId = Uuid::uuid4()->__toString();

        while ($this->exists($nextId)) {
            $nextId = Uuid::uuid4()->__toString();
        }

        return $nextId;
    }

    /**
     * @deprecated use GetByContext instead
     */
    public function getByOwner(ReferableModel $owner): Collection
    {
        throw new \Exception('No more usage of FragmentRepository::getByOwner');
        //
        //        if (! $context = $this->contextRepository->findByOwner($owner)) {
        //            return collect();
        //        }
        //
        //        return $context->fragments()
        //            ->get()
        //            ->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory->create($fragmentModel));
    }
}
