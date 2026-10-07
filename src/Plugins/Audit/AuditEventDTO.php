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

    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  array<string, mixed>  $context
     * @param  list<RichData>  $richData
     */
    public function __construct(
        public string $type,
        public string $actorType,
        public array $actorSnapshot,
        ?string $occurredAt = null,
        public string $category = 'general',
        public ?string $summary = null,
        public ?string $outcome = null,
        public array $context = [],
        public AuditModelCollectionDTO $models = new AuditModelCollectionDTO([]),
        public array $richData = [],
    ) {
        self::assertKey($type, 190, 'type');
        self::assertKey($category, 100, 'category');

        if (! in_array($actorType, ['admin', 'system', 'external'], true)) {
            throw new InvalidArgumentException('Invalid audit actor type.');
        }

        self::assertSnapshot($actorSnapshot, ['name', 'id'], 'actor');
        self::assertContext($context);

        foreach ($richData as $piece) {
            if (! $piece instanceof RichData || ($piece->model !== null && ! isset($models->all()[$piece->model]))) {
                throw new InvalidArgumentException('Invalid audit rich data model.');
            }
        }

        if ($summary !== null && mb_strlen($summary) > 500) {
            throw new InvalidArgumentException('Audit summary exceeds 500 characters.');
        }

        if ($outcome !== null && mb_strlen($outcome) > 100) {
            throw new InvalidArgumentException('Audit outcome exceeds 100 characters.');
        }

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
        $primaryModel = $this->models->primary();

        return [
            'type' => $this->type,
            'category' => $this->category,
            'outcome' => $this->outcome,
            'occurred_at' => $this->occurredAt,
            'summary' => $this->summary,
            'actor_type' => $this->actorType,
            'actor_snapshot' => $this->actorSnapshot,
            'model_type' => $primaryModel?->modelType,
            'model_id' => $primaryModel?->modelId,
            'model_snapshot' => $primaryModel?->modelSnapshot,
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
