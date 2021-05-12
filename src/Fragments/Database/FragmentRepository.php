<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionClass;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class FragmentRepository
{
    private Collection $prefetchedRecords;

    public function __construct()
    {
        $this->prefetchedRecords = collect();
    }

    /**
     * @param Model $owner
     *
     * @return Collection Fragmentable[]
     */
    public function getByOwner(Model $owner): Collection
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            return collect();
        }

        $fragmentModels = $context->fragments()->get();

        $this->prefetchRecords($fragmentModels);

        return $fragmentModels->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory($fragmentModel));
    }

    public function shared(): Collection
    {
        return FragmentModel::where('shared', 1)->get()->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory($fragmentModel));
    }

    /**
     * @param int $id
     * @return Fragmentable
     */
    public function find($id): Fragmentable
    {
        return $this->fragmentFactory(FragmentModel::findOrFail((int) $id));
    }

    private function prefetchRecords(Collection $fragmentModels): void
    {
        $fragmentModels->mapToGroups(function (FragmentModel $fragmentModel) {
            return [ModelReference::fromString($fragmentModel->model_reference)->className() => ModelReference::fromString($fragmentModel->model_reference)->id()];
        })->reject(function ($_modelIds, $className) {
            $reflection = new ReflectionClass($className);

            return ! $reflection->isSubclassOf(Model::class);
        })->each(function ($modelIds, $className) {
            $modelIds = $modelIds->filter(fn ($modelId) => $modelId !== 0);
            $records = $className::withoutGlobalScopes()->whereIn('id', $modelIds->toArray())->get();

            $records->each(function ($record) {
                $this->prefetchedRecords[$record->modelReference()->get()] = $record;
            });
        });
    }

    private function fragmentFactory(FragmentModel $fragmentModel): Fragmentable
    {
        return $this->prefetchedRecords
            ->get($fragmentModel->model_reference,  fn () => ModelReference::fromString($fragmentModel->model_reference)->instance())
            ->setFragmentModel($fragmentModel);
    }

    public function nextId(): int
    {
        // We would like to use uuid like (Uuid::uuid4()->__toString()); but the Asset library currently accepts integer(11) as entity_id in database
        $nextId = random_int(1000, 2000000000);

        while (FragmentModel::find($nextId)) {
            $nextId = random_int(1000, 2000000000);
        }

        return $nextId;
    }
}
