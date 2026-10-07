<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class AuditModelDTO
{
    private array $changes = [];

    /**
     * @param  array{name: string}  $modelSnapshot
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        public readonly string $modelType,
        public readonly string $modelId,
        public readonly array $modelSnapshot,
        public readonly array $context = [],
    ) {
        if (trim($modelType) === '' || mb_strlen($modelType) > 190 || trim($modelId) === '' || mb_strlen($modelId) > 190) {
            throw new InvalidArgumentException('Invalid audit model reference.');
        }

        if (array_keys($modelSnapshot) !== ['name'] || ! is_string($modelSnapshot['name']) || trim($modelSnapshot['name']) === '' || mb_strlen($modelSnapshot['name']) > 190) {
            throw new InvalidArgumentException('Invalid audit model snapshot.');
        }

        AuditEventDTO::assertContext($context);
    }

    /**
     * @param  list<string>|null  $paths  Null uses the model's auditChangePaths() selection; [] disables capture.
     * @param  list<string>  $exclusions
     */
    public function withChangesFrom(Model $model, ?array $paths = null, array $exclusions = []): self
    {
        $captured = clone $this;
        $captured->changes = ModelChanges::capture($model, $paths, $exclusions);

        return $captured;
    }

    /**
     * @return array{model_type: string, model_id: string, model_snapshot: array{name: string}, context: array<string, mixed>, changes: ?array}
     */
    public function toRecord(): array
    {
        return [
            'model_type' => $this->modelType,
            'model_id' => $this->modelId,
            'model_snapshot' => $this->modelSnapshot,
            'context' => $this->context,
            'changes' => $this->changes === [] ? null : $this->changes,
        ];
    }
}
