<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\ManagedModels\Actions\SortModels;

final class AddFragmentModel
{
    private SortModels $sortModels;

    public function __construct(SortModels $sortModels)
    {
        $this->sortModels = $sortModels;
    }

    public function handle(Model $owner, FragmentModel $fragmentModel, int $order): void
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            $context = ContextModel::createForOwner($owner);
        }

        if ($context->fragments()->where('id', $fragmentModel->id)->exists()) {
            throw new FragmentAlreadyAdded('Fragment [' . $fragmentModel->id . '] was already added to owner [' . $owner->modelReference()->get().']');
        }

        $indices = $this->fetchSortIndices($context, $order, $fragmentModel->id);

        $context->fragments()->attach($fragmentModel->id);

        $this->sortModels->handleFragments($owner, $indices);
    }

    /**
     * @param ContextModel $context
     * @param int $order
     * @param int $fragmentModelId
     * @return mixed
     */
    private function fetchSortIndices(ContextModel $context, int $order, int $fragmentModelId): array
    {
        $indices = $context->fragments()->get()->map(function ($fragment) {
            return $fragment->pivot->fragment_id;
        })->values()->all();

        array_splice($indices, $order, 0, $fragmentModelId);

        return $indices;
    }
}
