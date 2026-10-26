<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentMoved;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;

final class MoveFragment
{
    public function __construct(
        private FragmentRepository $fragments,
        private ContextOwnerRepository $owners,
    ) {}

    public function handle(string $contextId, string $fragmentId, ?string $newParentId, ?int $order = null): bool
    {
        if ($order !== null && $order < 0) {
            throw new InvalidArgumentException('The order must be zero or greater.');
        }

        $moved = DB::transaction(function () use ($contextId, $fragmentId, $newParentId, $order): bool {
            ContextModel::query()->lockForUpdate()->findOrFail($contextId);

            $rows = DB::table('context_fragment_tree')
                ->where('context_id', $contextId)
                ->orderBy('order')
                ->get();

            $byId = $rows->keyBy('child_id');
            $current = $byId->get($fragmentId);

            if (! $current) {
                throw new InvalidArgumentException('The fragment does not exist in this context.');
            }

            $fragment = $this->fragments->findInContext($fragmentId, $contextId);

            if ($newParentId === null) {
                $allowed = $this->owners->findOwner($contextId)->allowedFragments();
            } else {
                if (! $byId->has($newParentId)) {
                    throw new InvalidArgumentException('The selected parent does not exist in this context.');
                }

                // Walk up the destination branch to prevent cycles.
                $visited = [];
                for ($id = $newParentId; $id !== null; $id = $byId->get($id)?->parent_id) {
                    if ($id === $fragmentId || isset($visited[$id])) {
                        throw new InvalidArgumentException('A fragment cannot be moved below itself or one of its descendants.');
                    }

                    if (! $byId->has($id)) {
                        throw new InvalidArgumentException('The selected parent has an invalid parent chain.');
                    }

                    $visited[$id] = true;
                }

                $allowed = $this->fragments->findInContext($newParentId, $contextId)->allowedFragments();
            }

            if (! in_array($fragment::class, $allowed, true)) {
                throw new InvalidArgumentException('This fragment type is not allowed at the selected location.');
            }

            $oldParentId = $current->parent_id;

            if ($oldParentId === $newParentId && $order === null) {
                return false;
            }

            $siblings = fn (?string $parentId) => $rows
                ->filter(fn ($row) => $row->parent_id === $parentId)
                ->pluck('child_id')
                ->values()
                ->all();

            $targetIds = array_values(array_filter($siblings($newParentId), fn ($id) => $id !== $fragmentId));
            $insertAt = $order === null ? count($targetIds) : min($order, count($targetIds));
            array_splice($targetIds, $insertAt, 0, [$fragmentId]);

            if ($oldParentId === $newParentId && $targetIds === $siblings($oldParentId)) {
                return false;
            }

            DB::table('context_fragment_tree')
                ->where('context_id', $contextId)
                ->where('child_id', $fragmentId)
                ->update(['parent_id' => $newParentId]);

            $this->reindex($contextId, $targetIds);

            if ($oldParentId !== $newParentId) {
                $oldIds = array_values(array_filter($siblings($oldParentId), fn ($id) => $id !== $fragmentId));
                $this->reindex($contextId, $oldIds);
            }

            return true;
        });

        if ($moved) {
            event(new FragmentMoved($contextId, $fragmentId));
        }

        return $moved;
    }

    private function reindex(string $contextId, array $fragmentIds): void
    {
        foreach ($fragmentIds as $order => $fragmentId) {
            DB::table('context_fragment_tree')
                ->where('context_id', $contextId)
                ->where('child_id', $fragmentId)
                ->update(['order' => $order]);
        }
    }
}
