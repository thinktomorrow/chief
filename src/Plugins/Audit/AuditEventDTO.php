<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use InvalidArgumentException;

final readonly class AuditEventDTO
{
    public DateTimeImmutable $occurredAt;

    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  array{name: string}|null  $modelSnapshot
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
    ) {
        self::assertKey($type, 190, 'type');
        self::assertKey($category, 100, 'category');

        if (! in_array($actorType, ['admin', 'system', 'external'], true)) {
            throw new InvalidArgumentException('Invalid audit actor type.');
        }

        self::assertSnapshot($actorSnapshot, ['name', 'id'], 'actor');

        if ($summary !== null && mb_strlen($summary) > 500) {
            throw new InvalidArgumentException('Audit summary exceeds 500 characters.');
        }

        if ($outcome !== null && mb_strlen($outcome) > 100) {
            throw new InvalidArgumentException('Audit outcome exceeds 100 characters.');
        }

        if ($modelType !== null || $modelId !== null || $modelSnapshot !== null) {
            if ($modelType === null || $modelId === null || $modelSnapshot === null) {
                throw new InvalidArgumentException('Audit model context requires type, id and snapshot.');
            }

            self::assertText($modelType, 190, 'model type');
            self::assertText($modelId, 190, 'model id');
            self::assertSnapshot($modelSnapshot, ['name'], 'model');
        }

        try {
            $this->occurredAt = (new DateTimeImmutable($occurredAt ?? 'now'))->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception $exception) {
            throw new InvalidArgumentException('Invalid audit event time.', previous: $exception);
        }
    }

    /**
     * @return array{type: string, category: string, outcome: ?string, occurred_at: DateTimeImmutable, summary: ?string, actor_type: string, actor_snapshot: array{name: string, id?: string}, model_type: ?string, model_id: ?string, model_snapshot: array{name: string}|null}
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
        ];
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
