<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Events;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Admin\Users\User;

final readonly class ChiefActionCompleted
{
    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  list<array{type: string, id: string, name: string}>  $models
     */
    private function __construct(
        public string $action,
        public string $actorType,
        public array $actorSnapshot,
        public array $models,
        public string $occurredAt,
    ) {}

    public static function forModels(string $action, Model ...$models): self
    {
        $actor = auth('chief')->user();
        $snapshot = $actor instanceof User
            ? ['name' => $actor->fullname, 'id' => (string) $actor->getKey()]
            : ['name' => 'System'];

        return new self(
            $action,
            $actor instanceof User ? 'admin' : 'system',
            $snapshot,
            array_map(static fn (Model $model): array => [
                'type' => $model->getMorphClass(),
                'id' => (string) $model->getKey(),
                'name' => class_basename($model).' #'.$model->getKey(),
            ], $models),
            now()->toIso8601String(),
        );
    }
}
