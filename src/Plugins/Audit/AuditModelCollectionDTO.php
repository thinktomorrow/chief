<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use InvalidArgumentException;

final readonly class AuditModelCollectionDTO
{
    /**
     * @param  list<AuditModelDTO>  $models  The first model is the primary model.
     */
    public function __construct(private array $models)
    {
        if (! array_is_list($models)) {
            throw new InvalidArgumentException('Audit models must be an ordered list.');
        }

        foreach ($models as $model) {
            if (! $model instanceof AuditModelDTO) {
                throw new InvalidArgumentException('Audit models must be AuditModelDTO values.');
            }
        }

        $modelKeys = array_map(fn (AuditModelDTO $model): string => $model->modelType."\0".$model->modelId, $models);

        if (count($modelKeys) !== count(array_unique($modelKeys))) {
            throw new InvalidArgumentException('Duplicate audit model reference.');
        }
    }

    public function primary(): ?AuditModelDTO
    {
        return $this->models[0] ?? null;
    }

    /**
     * @return list<AuditModelDTO>
     */
    public function all(): array
    {
        return $this->models;
    }
}
