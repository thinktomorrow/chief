<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use InvalidArgumentException;

final readonly class AuditModelDTO
{
    /**
     * @param  array{name: string}  $modelSnapshot
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        public string $modelType,
        public string $modelId,
        public array $modelSnapshot,
        public array $context = [],
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
     * @return array{model_type: string, model_id: string, model_snapshot: array{name: string}, context: array<string, mixed>}
     */
    public function toRecord(): array
    {
        return [
            'model_type' => $this->modelType,
            'model_id' => $this->modelId,
            'model_snapshot' => $this->modelSnapshot,
            'context' => $this->context,
        ];
    }
}
