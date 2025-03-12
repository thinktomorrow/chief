<?php

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentFactory;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class GetShareableFragments
{
    private FragmentFactory $fragmentFactory;

    private FragmentRepository $fragmentRepository;

    private array $filters = [];

    private int $limit = 30;

    public function __construct(FragmentRepository $fragmentRepository, FragmentFactory $fragmentFactory)
    {
        $this->fragmentFactory = $fragmentFactory;
        $this->fragmentRepository = $fragmentRepository;
    }

    public function filterByTypes(array $types): self
    {
        $this->filters['types'] = $types;

        return $this;
    }

    public function filterByShared(): self
    {
        $this->filters['shared'] = true;

        return $this;
    }

    public function filterByOwners(array $owners): self
    {
        $this->filters['owners'] = $owners;

        return $this;
    }

    public function excludeAlreadySelected(): self
    {
        $this->filters['exclude_already_selected'] = true;

        return $this;
    }

    /**
     * All child fragments residing in a root shared fragment are also marked as shared.
     *
     * By design, we only allow root fragments to be available for sharing.
     * This reduces edge cases in UX and simplifies the user experience.
     *
     * If needed, you can switch this off to return child fragments as well.
     *
     * @return $this
     */
    public function includeNonRootFragments(): self
    {
        $this->filters['include_non_root_fragments'] = true;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function get(string $contextId): Collection
    {
        $builder = FragmentModel::query()
            ->limit($this->limit)
            ->join('context_fragment_tree', 'context_fragments.id', '=', 'context_fragment_tree.child_id')
            ->join('contexts', 'context_fragment_tree.context_id', '=', 'contexts.id')
            ->select('context_fragments.*')
            ->groupBy('context_fragments.id');

        if ($isFilteringByType = isset($this->filters['types']) && count($this->filters['types']) > 0) {
            $builder = $this->queryFilterByTypes($builder, $this->filters['types']);
        }

        if ($isFilteringByOwner = isset($this->filters['owners']) && count($this->filters['owners']) > 0) {
            $builder = $this->queryFilterByOwners($builder, $this->filters['owners']);
        }

        // Default only top shared ones
        if (! $isFilteringByType && ! $isFilteringByOwner) {
            // Get top used already shared ones...
            $builder = $this->queryFilterByUsage($builder);
        }

        if (isset($this->filters['shared']) && $this->filters['shared']) {
            $builder = $this->queryFilterByShared($builder);
        }

        if (! isset($this->filters['include_non_root_fragments']) || $this->filters['include_non_root_fragments'] == false) {
            $builder->whereNull('context_fragment_tree.parent_id');
        }

        $currentFragmentIds = $this->fragmentRepository->getFragmentIdsByContext($contextId);

        $collection = $builder
            ->get()
            ->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory->create($fragmentModel))
            ->map(function ($fragment) use ($currentFragmentIds) {
                $fragment->is_already_selected = in_array($fragment->getFragmentId(), $currentFragmentIds);

                return $fragment;
            });

        // Make sure we don't return the fragments that are already used in current context
        if (isset($this->filters['exclude_already_selected']) && $this->filters['exclude_already_selected']) {
            return $collection->reject(fn ($fragment) => $fragment->is_already_selected);
        }

        return $collection;
    }

    private function queryFilterByTypes($builder, array $classReferences): Builder
    {
        $classReferences = $this->expandedClassReferences($classReferences);

        return $builder->where(function ($query) use ($classReferences) {
            $query->where(DB::raw('1=0'));
            foreach ($classReferences as $classReference) {
                $query->orWhere('key', '=', $classReference);
            }
        });
    }

    private function queryFilterByOwners($builder, array $modelReferences): Builder
    {
        $modelReferences = array_map(fn ($value) => ModelReference::fromString($value), $modelReferences);

        return $builder
            ->where(function ($query) use ($modelReferences) {
                $query->where(DB::raw('1=0'));

                foreach ($modelReferences as $modelReference) {
                    $query->orWhere(function ($query) use ($modelReference) {
                        $query->where('contexts.owner_type', '=', $modelReference->shortClassName());
                        $query->where('contexts.owner_id', '=', $modelReference->id());
                    });
                }
            });
    }

    private function queryFilterByUsage($builder): Builder
    {
        return $builder
            ->addSelect(DB::raw("count('context_fragment_tree.child_id') AS 'usage'"))
            ->orderBy('usage', 'DESC');
    }

    private function queryFilterByShared($builder): Builder
    {
        return $builder
            ->whereJsonContains('meta->shared', true);
    }

    private function expandedClassReferences(array $classNames): array
    {
        $expanded = [];

        foreach ($classNames as $className) {
            $modelReference = ModelReference::fromStatic($className);
            $expanded[] = addslashes($modelReference->className());
            $expanded[] = $modelReference->shortClassName();
        }

        return $expanded;
    }
}
