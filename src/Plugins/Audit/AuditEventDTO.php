<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use JsonException;

final readonly class AuditEventDTO
{
    private const MAX_CONTEXT_DEPTH = 64;

    public DateTimeImmutable $occurredAt;

    /** @var list<AuditModelDTO> */
    public array $relatedModels;

    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  array{name: string}|null  $modelSnapshot
     * @param  array<string, mixed>  $context
     * @param  list<array{modelType: string, modelId: string, modelSnapshot: array{name: string}, context?: array<string, mixed>}>  $relatedModels
     * @param  array<string, mixed>  $modelContext
     */
    public function __construct(
        public string $type,
        public string $actorType,
        public array $actorSnapshot,
        ?string $occurredAt = null,
        public string $category = 'general',
        public ?string $summary = null,
        public ?string $outcome = null,
        public ?string $modelType = null,
        public ?string $modelId = null,
        public ?array $modelSnapshot = null,
        public array $context = [],
        array $relatedModels = [],
        array $modelContext = [],
    ) {
        self::assertKey($type, 190, 'type');
        self::assertKey($category, 100, 'category');

        if (! in_array($actorType, ['admin', 'system', 'external'], true)) {
            throw new InvalidArgumentException('Invalid audit actor type.');
        }

        self::assertSnapshot($actorSnapshot, ['name', 'id'], 'actor');
        self::assertContext($context);

        if ($summary !== null && mb_strlen($summary) > 500) {
            throw new InvalidArgumentException('Audit summary exceeds 500 characters.');
        }

        if ($outcome !== null && mb_strlen($outcome) > 100) {
            throw new InvalidArgumentException('Audit outcome exceeds 100 characters.');
        }

        if ($modelType !== null || $modelId !== null || $modelSnapshot !== null || $modelContext !== []) {
            if ($modelType === null || $modelId === null || $modelSnapshot === null) {
                throw new InvalidArgumentException('Audit model context requires type, id and snapshot.');
            }

            $primaryModel = new AuditModelDTO($modelType, $modelId, $modelSnapshot, $modelContext);
        }

        $models = isset($primaryModel) ? [$primaryModel] : [];

        foreach ($relatedModels as $relatedModel) {
            if (! is_array($relatedModel)
                || array_diff(['modelType', 'modelId', 'modelSnapshot'], array_keys($relatedModel))
                || array_diff(array_keys($relatedModel), ['modelType', 'modelId', 'modelSnapshot', 'context'])
                || ! is_string($relatedModel['modelType'])
                || ! is_string($relatedModel['modelId'])
                || ! is_array($relatedModel['modelSnapshot'])
                || (isset($relatedModel['context']) && ! is_array($relatedModel['context']))) {
                throw new InvalidArgumentException('Invalid audit related model context.');
            }

            $models[] = new AuditModelDTO(
                modelType: $relatedModel['modelType'],
                modelId: $relatedModel['modelId'],
                modelSnapshot: $relatedModel['modelSnapshot'],
                context: $relatedModel['context'] ?? [],
            );
        }

        $modelKeys = array_map(fn (AuditModelDTO $model): string => $model->modelType."\0".$model->modelId, $models);

        if (count($modelKeys) !== count(array_unique($modelKeys))) {
            throw new InvalidArgumentException('Duplicate audit model reference.');
        }

        $this->relatedModels = $models;

        try {
            $this->occurredAt = (new DateTimeImmutable($occurredAt ?? now()->toIso8601String()))->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception $exception) {
            throw new InvalidArgumentException('Invalid audit event time.', previous: $exception);
        }
    }

    /**
     * @return array{type: string, category: string, outcome: ?string, occurred_at: DateTimeImmutable, summary: ?string, actor_type: string, actor_snapshot: array{name: string, id?: string}, model_type: ?string, model_id: ?string, model_snapshot: array{name: string}|null, context: array<string, mixed>}
     */
    public function toRecord(): array
    {
        return [
            'type' => $this->type,
            'category' => $this->category,
            'outcome' => $this->outcome,
            'occurred_at' => $this->occurredAt,
            'summary' => $this->summary,
            'actor_type' => $this->actorType,
            'actor_snapshot' => $this->actorSnapshot,
            'model_type' => $this->modelType,
            'model_id' => $this->modelId,
            'model_snapshot' => $this->modelSnapshot,
            'context' => $this->context,
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public static function assertContext(array $context): void
    {
        self::assertContextValues($context);

        try {
            json_encode($context, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidArgumentException('Invalid audit context.', previous: $exception);
        }
    }

    private static function assertContextValues(array $values, int $depth = 0): void
    {
        if ($depth > self::MAX_CONTEXT_DEPTH) {
            throw new InvalidArgumentException('Audit context is too deeply nested.');
        }

        foreach ($values as $value) {
            if (is_array($value)) {
                self::assertContextValues($value, $depth + 1);
            } elseif (! is_scalar($value) && $value !== null) {
                throw new InvalidArgumentException('Audit context must contain only safe scalar or array values.');
            }
        }
    }

    private static function assertKey(string $value, int $maxLength, string $field): void
    {
        if (mb_strlen($value) > $maxLength || ! preg_match('/^[a-z0-9][a-z0-9._-]*$/D', $value)) {
            throw new InvalidArgumentException('Invalid audit '.$field.'.');
        }
    }

    private static function assertText(string $value, int $maxLength, string $field): void
    {
        if (trim($value) === '' || mb_strlen($value) > $maxLength) {
            throw new InvalidArgumentException('Invalid audit '.$field.'.');
        }
    }

    /**
     * @param  array{name: string, id?: string}  $snapshot
     * @param  list<string>  $allowedFields
     */
    private static function assertSnapshot(array $snapshot, array $allowedFields, string $field): void
    {
        if (array_diff(array_keys($snapshot), $allowedFields)
            || ! isset($snapshot['name'])
            || ! is_string($snapshot['name'])) {
            throw new InvalidArgumentException('Invalid audit '.$field.' snapshot.');
        }

        self::assertText($snapshot['name'], 190, $field.' name');

        if (array_key_exists('id', $snapshot)) {
            if (! is_string($snapshot['id']) || mb_strlen($snapshot['id']) > 190) {
                throw new InvalidArgumentException('Invalid audit actor id.');
            }
        }
    }
}
