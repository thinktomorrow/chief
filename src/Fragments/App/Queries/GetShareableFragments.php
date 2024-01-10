<?php

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentFactory;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
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

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function get(string $contextId): Collection
    {
        $builder = FragmentModel::query()
            ->limit($this->limit);

        if ($isFilteringByType = isset($this->filters['types']) && count($this->filters['types']) > 0) {
            $builder = $this->queryFilterByTypes($builder, $this->filters['types']);
        }

        if ($isFilteringByOwner = isset($this->filters['owners']) && count($this->filters['owners']) > 0) {
            $builder = $this->queryFilterByOwners($builder, $this->filters['owners']);
        }

        // Default only top shared ones
        if (! $isFilteringByType && ! $isFilteringByOwner) {
            // Get top used already shared ones...
            $builder = $this->filterByUsage($builder);
        }

        $currentFragmentIds = $this->fragmentRepository->getByContext($contextId)->map(fn ($fragment) => $fragment->getFragmentId())->toArray();

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
            $query->where(DB::raw("1=0"));
            foreach ($classReferences as $classReference) {
                $query->orWhere('key', '=', $classReference);
            }
        });
    }

    private function queryFilterByOwners($builder, array $modelReferences): Builder
    {
        $modelReferences = array_map(fn ($value) => ModelReference::fromString($value), $modelReferences);

        return $builder
            ->join('context_fragment_lookup', 'context_fragments.id', '=', 'context_fragment_lookup.fragment_id')
            ->join('contexts', 'context_fragment_lookup.context_id', '=', 'contexts.id')
            ->select('context_fragments.*')
            ->groupBy('context_fragments.id')
            ->where(function ($query) use ($modelReferences) {
                $query->where(DB::raw("1=0"));

                foreach ($modelReferences as $modelReference) {
                    $query->orWhere(function ($query) use ($modelReference) {
                        $query->where('contexts.owner_type', '=', $modelReference->shortClassName());
                        $query->where('contexts.owner_id', '=', $modelReference->id());
                    });
                }
            });
    }

    private function filterByUsage($builder): Builder
    {
        return $builder
            ->join('context_fragment_lookup', 'context_fragments.id', '=', 'context_fragment_lookup.fragment_id')
            ->select(['context_fragments.*', DB::raw("count('context_fragment_lookup.fragment_id') AS 'usage'")])
            ->groupBy('context_fragments.id')
            ->orderBy('usage', 'DESC');
    }

    private function expandedClassReferences(array $classNames): array
    {
        $expanded = [];

        foreach ($classNames as $className) {
            $modelReference = ModelReference::fromStatic($className);
            $expanded[] = addSlashes($modelReference->className());
            $expanded[] = $modelReference->shortClassName();
        }

        return $expanded;
    }
}
