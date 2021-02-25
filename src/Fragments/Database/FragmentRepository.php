<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class FragmentRepository
{
    private Collection $prefetchedRecords;

    public function __construct()
    {
        $this->prefetchedRecords = collect();
    }

    /**
     * @param FragmentsOwner $owner
     * @return Collection Fragmentable[]
     */
    public function getByOwner(Model $owner): Collection
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            return collect();
        }

        $fragmentModels = $context->fragments()->get();

        $this->prefetchRecords($fragmentModels);

        return $fragmentModels->map(
            fn (FragmentModel $fragmentModel) => $this->fragmentFactory($fragmentModel)
        );
    }

    public function shared(): Collection
    {
        return FragmentModel::where('shared',1)->get()->map(
            fn (FragmentModel $fragmentModel) => $this->fragmentFactory($fragmentModel)
        );
    }

    public function find(string $id): Fragmentable
    {
        return $this->fragmentFactory(FragmentModel::findOrFail($id));
    }

    private function prefetchRecords(Collection $fragmentModels)
    {
        $fragmentModels->mapToGroups(function (FragmentModel $fragmentModel) {
            return [ModelReference::fromString($fragmentModel->model_reference)->className() => ModelReference::fromString($fragmentModel->model_reference)->id()];
        })->reject(function ($modelIds, $className) {
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

    public function nextId(): string
    {
        return Uuid::uuid4()->__toString();
    }
}
